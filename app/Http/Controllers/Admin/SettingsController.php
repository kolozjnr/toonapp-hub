<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    /**
     * The EmailJS endpoint the browser and the test button both post to.
     */
    private const EMAILJS_SEND_URL = 'https://api.emailjs.com/api/v1.0/email/send';

    /**
     * Show the settings page with everything saved so far.
     */
    public function show(): View
    {
        $settings = Setting::values(array_merge(Setting::CONTACT_KEYS, Setting::EMAIL_KEYS));

        return view('admin.settings', [
            'settings' => $settings,
            'emailReady' => collect(Setting::EMAIL_KEYS)->every(fn (string $key): bool => filled($settings[$key])),
        ]);
    }

    /**
     * Save the contact details shown across the store.
     */
    public function updateContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'support_email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30', 'regex:/^[+0-9 ()-]+$/'],
            'instagram_url' => ['nullable', Rule::url()->protocols(['https']), 'max:2048'],
            'youtube_url' => ['nullable', Rule::url()->protocols(['https']), 'max:2048'],
            'support_hours' => ['nullable', 'string', 'max:80'],
        ], [
            'support_email.email' => "That email doesn't look right.",
            'whatsapp.regex' => 'Add the WhatsApp number with country code, like +234 801 234 5678.',
            'instagram_url.url' => 'The Instagram link should start with https://',
            'youtube_url.url' => 'The YouTube link should start with https://',
        ]);

        Setting::saveMany($this->trimmed($validated));

        return redirect()->route('admin.settings')->with('toast', 'Contact details saved');
    }

    /**
     * Save the EmailJS keys used to send sign-up, reset and purchase emails.
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'emailjs_public_key' => ['nullable', 'string', 'max:128'],
            'emailjs_service_id' => ['nullable', 'string', 'max:64'],
            'template_verify' => ['nullable', 'string', 'max:64'],
            'template_reset' => ['nullable', 'string', 'max:64'],
            'template_purchase' => ['nullable', 'string', 'max:64'],
        ]);

        Setting::saveMany($this->trimmed($validated));

        return redirect()->route('admin.settings')->with('toast', 'Email settings saved');
    }

    /**
     * Ask EmailJS to deliver one message to the given address so the saved
     * keys can be confirmed without waiting for the next sign-up.
     */
    public function testEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ], [
            'test_email.required' => 'Enter the email address to send the test to.',
            'test_email.email' => 'Enter the email address to send the test to.',
        ]);

        $settings = Setting::values(Setting::EMAIL_KEYS);

        $missing = collect(Setting::EMAIL_KEYS)->first(fn (string $key): bool => blank($settings[$key]));

        if ($missing !== null) {
            return redirect()
                ->route('admin.settings')
                ->with('error', 'Save your EmailJS public key, service ID and template IDs first.');
        }

        try {
            $response = Http::connectTimeout(3)
                ->timeout(10)
                ->post(self::EMAILJS_SEND_URL, [
                    'service_id' => $settings['emailjs_service_id'],
                    'template_id' => $settings['template_verify'],
                    'user_id' => $settings['emailjs_public_key'],
                    'template_params' => [
                        'to_email' => $validated['test_email'],
                        'to_name' => 'there',
                        'store_name' => config('app.name'),
                        'code' => '123456',
                    ],
                ]);
        } catch (ConnectionException $exception) {
            return redirect()
                ->route('admin.settings')
                ->with('error', "EmailJS couldn't be reached just now. Check your connection and try again.");
        }

        if ($response->failed()) {
            $detail = Str::limit(trim($response->body()), 160, '…');

            return redirect()
                ->route('admin.settings')
                ->with('error', 'EmailJS refused the test: '.($detail !== '' ? $detail : 'the request was rejected.'));
        }

        return redirect()
            ->route('admin.settings')
            ->with('toast', 'Test email sent to '.$validated['test_email'].'. Check your inbox.');
    }

    /**
     * Change the email and password used to reach the admin dashboard.
     */
    public function updateAdmin(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)->numbers()],
        ], [
            'email.required' => 'Enter the admin email.',
            'email.email' => 'Enter a valid email.',
            'current_password.required' => 'Enter your current password.',
            'current_password.current_password' => "That's not your current password.",
            'password.confirmed' => "Passwords don't match.",
            'password.min' => 'Use at least 8 characters.',
        ]);

        $user->email = Str::lower($validated['email']);

        if (filled($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.settings')->with('toast', 'Admin login updated');
    }

    /**
     * Download the plugins, customers, orders and settings as one JSON file.
     */
    public function backup(): StreamedResponse
    {
        $payload = [
            'app' => 'toonapp-hub',
            'type' => 'backup',
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'plugins' => $this->rows('plugins'),
            'customers' => $this->customerRows(),
            'customer_roles' => $this->customerRoles(),
            'orders' => $this->rows('payments'),
            'settings' => Setting::values(),
        ];

        return response()->streamDownload(function () use ($payload): void {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }, 'toonapp-backup-'.now()->format('Y-m-d-His').'.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Replace the plugins, customers, orders and settings with the ones in
     * the uploaded backup file.
     */
    public function restore(Request $request): RedirectResponse
    {
        $request->validate([
            'backup' => ['required', 'file', 'max:10240'],
        ], [
            'backup.required' => 'Choose the backup file to restore.',
            'backup.file' => 'That file could not be read. Choose a JSON backup file.',
            'backup.max' => 'That backup file is too large.',
        ]);

        $backup = json_decode((string) file_get_contents($request->file('backup')->getRealPath()), true);

        if (! is_array($backup) || ($backup['app'] ?? null) !== 'toonapp-hub' || ($backup['type'] ?? null) !== 'backup') {
            return redirect()->route('admin.settings')->with('error', 'That file is not a ToonApp Hub backup.');
        }

        $plugins = $backup['plugins'] ?? null;
        $customers = $backup['customers'] ?? null;
        $orders = $backup['orders'] ?? null;
        $settings = $backup['settings'] ?? null;

        if (! is_array($plugins) || ! is_array($customers) || ! is_array($orders) || ! is_array($settings)) {
            return redirect()
                ->route('admin.settings')
                ->with('error', 'That backup is missing its plugins, customers, orders or settings.');
        }

        $roles = is_array($backup['customer_roles'] ?? null) ? $backup['customer_roles'] : [];

        try {
            DB::transaction(function () use ($plugins, $customers, $orders, $settings, $roles): void {
                $staffIds = $this->staffIds()->all();
                $removedIds = DB::table('users')->whereNotIn('id', $staffIds)->pluck('id')->all();

                DB::table('payments')->delete();
                DB::table('plugins')->delete();

                if ($removedIds !== []) {
                    DB::table('model_has_roles')
                        ->where('model_type', User::class)
                        ->whereIn('model_id', $removedIds)
                        ->delete();
                    DB::table('users')->whereIn('id', $removedIds)->delete();
                }

                $this->insertRows('users', $customers, ['id', 'name', 'first_name', 'last_name', 'email', 'country', 'newsletter', 'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at']);
                $this->insertRows('plugins', $plugins, ['id', 'name', 'slug', 'tagline', 'description', 'features', 'youtube_url', 'cover_image', 'gallery', 'price', 'old_price', 'category', 'badge', 'hosts', 'version', 'requirements', 'download_url', 'is_live', 'created_by', 'created_at', 'updated_at']);
                $this->insertRows('payments', $orders, ['id', 'user_id', 'reference', 'idempotency_key', 'cart_hash', 'amount', 'currency', 'gateway', 'status', 'items', 'authorization_url', 'gateway_payload', 'failure_reason', 'verified_at', 'paid_at', 'created_at', 'updated_at']);

                $this->restoreCustomerRoles($customers, $roles);

                Setting::saveMany($this->scalarSettings($settings));
            });
        } catch (QueryException $exception) {
            return redirect()
                ->route('admin.settings')
                ->with('error', "That backup doesn't match this store, so nothing was changed.");
        }

        return redirect()->route('admin.settings')->with(
            'success',
            'Restored '.count($plugins).' '.Str::plural('plugin', count($plugins))
                .', '.count($customers).' '.Str::plural('customer', count($customers))
                .' and '.count($orders).' '.Str::plural('order', count($orders)).'.'
        );
    }

    /**
     * The rows of a table as plain arrays, keeping ids and timestamps so a
     * restore lines orders back up with the customers that placed them.
     *
     * @return list<array<string, mixed>>
     */
    private function rows(string $table): array
    {
        return DB::table($table)->orderBy('id')->get()->map(fn ($row): array => (array) $row)->all();
    }

    /**
     * The shopper accounts only: every user without a staff role.
     *
     * @return list<array<string, mixed>>
     */
    private function customerRows(): array
    {
        return DB::table('users')
            ->whereNotIn('id', $this->staffIds())
            ->orderBy('id')
            ->get()
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    /**
     * The ids of the accounts that run the store and are never part of a
     * backup or a restore.
     *
     * @return Collection<int, int|string>
     */
    private function staffIds(): Collection
    {
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.name', User::STAFF_ROLES)
            ->where('model_has_roles.model_type', User::class)
            ->pluck('model_has_roles.model_id');
    }

    /**
     * The role names each shopper holds, keyed by user id.
     *
     * @return array<string, list<string>>
     */
    private function customerRoles(): array
    {
        $staffIds = $this->staffIds()->map(fn ($id): int => (int) $id)->all();

        $roles = [];

        $rows = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->get();

        foreach ($rows as $row) {
            if (in_array((int) $row->model_id, $staffIds, true)) {
                continue;
            }

            $roles[(string) $row->model_id][] = (string) $row->name;
        }

        return $roles;
    }

    /**
     * Insert only the columns the table actually has, so a backup from an
     * older version of the store still restores cleanly.
     *
     * @param  list<array<string, mixed>>  $rows
     * @param  list<string>  $columns
     */
    private function insertRows(string $table, array $rows, array $columns): void
    {
        $rows = collect($rows)
            ->filter(fn ($row): bool => is_array($row))
            ->map(fn (array $row): array => array_intersect_key($row, array_flip($columns)))
            ->reject(fn (array $row): bool => $row === [])
            ->values()
            ->all();

        if ($rows !== []) {
            DB::table($table)->insert($rows);
        }
    }

    /**
     * Give every restored shopper the roles recorded in the backup.
     *
     * @param  list<array<string, mixed>>  $customers
     * @param  array<string, list<string>>  $roles
     */
    private function restoreCustomerRoles(array $customers, array $roles): void
    {
        foreach ($customers as $customer) {
            $id = is_array($customer) ? ($customer['id'] ?? null) : null;

            if ($id === null) {
                continue;
            }

            foreach ($roles[(string) $id] ?? ['user'] as $roleName) {
                $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

                DB::table('model_has_roles')->insert([
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $id,
                ]);
            }
        }
    }

    /**
     * Only string settings survive a restore: the table stores text.
     *
     * @param  array<string, mixed>  $settings
     * @return array<string, string|null>
     */
    private function scalarSettings(array $settings): array
    {
        $safe = [];

        foreach ($settings as $key => $value) {
            if (is_string($key) && strlen($key) <= 64 && (is_string($value) || $value === null)) {
                $safe[$key] = $value;
            }
        }

        return $safe;
    }

    /**
     * The validated panel values with empty fields stored as null and
     * surrounding whitespace removed.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, string|null>
     */
    private function trimmed(array $validated): array
    {
        $values = [];

        foreach ($validated as $key => $value) {
            $value = is_string($value) ? trim($value) : $value;
            $values[$key] = $value === '' ? null : $value;
        }

        return $values;
    }
}
