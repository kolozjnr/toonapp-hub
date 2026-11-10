<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsletterRequest;
use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    /**
     * Show the composer, the size of each audience and the updates sent so far.
     */
    public function index(): View
    {
        $subscriberIds = $this->subscribers()->pluck('id');

        return view('admin.newsletter', [
            'subscriberCount' => $subscriberIds->count(),
            'countNote' => $this->countNote($subscriberIds->count()),
            'audiences' => Newsletter::AUDIENCE_LABELS,
            'plugins' => Plugin::query()->orderBy('name')->get(),
            'ownerCounts' => array_map('count', $this->ownersByPlugin($subscriberIds)),
            'newsletters' => Newsletter::query()->with(['plugin', 'sender'])->latest()->limit(15)->get(),
            'newsletterCount' => Newsletter::query()->count(),
        ]);
    }

    /**
     * Send the update to the chosen audience and keep it in the sent list.
     */
    public function store(NewsletterRequest $request): RedirectResponse
    {
        $audience = (string) $request->input('audience');
        $plugin = $audience === Newsletter::AUDIENCE_PLUGIN
            ? Plugin::query()->findOrFail($request->input('plugin_id'))
            : null;

        $recipients = $this->recipients($audience, $plugin);

        if ($recipients->isEmpty()) {
            return back()->with('error', $this->nobodyMessage($plugin));
        }

        $newsletter = Newsletter::query()->create($this->attributes($request, $plugin, $recipients->count()));

        $this->deliver($recipients, $newsletter);

        return redirect()
            ->route('admin.newsletter')
            ->with('success', 'Newsletter sent to '.$newsletter->recipientsLabel().'.');
    }

    /**
     * Send a past update again to the audience it was written for.
     */
    public function resend(Newsletter $newsletter): RedirectResponse
    {
        $recipients = $this->recipients($newsletter->audience, $newsletter->plugin);

        if ($recipients->isEmpty()) {
            return back()->with('error', $this->nobodyMessage($newsletter->plugin));
        }

        $this->deliver($recipients, $newsletter);

        return back()->with(
            'success',
            'Sent again to '.$recipients->count().' '.Str::plural('subscriber', $recipients->count()).'.'
        );
    }

    /**
     * Drop a past update from the list. The emails have already gone out, so
     * only the record is removed.
     */
    public function destroy(Newsletter $newsletter): RedirectResponse
    {
        $newsletter->delete();

        return back()->with('success', 'Newsletter deleted.');
    }

    /**
     * The shoppers who opted in to the newsletter.
     */
    private function subscribers(): Builder
    {
        return User::query()->shoppers()->where('newsletter', true);
    }

    /**
     * The shoppers a send goes to: everyone who opted in, or the opted-in
     * shoppers who own one plugin.
     *
     * @return Collection<int, User>
     */
    private function recipients(string $audience, ?Plugin $plugin): Collection
    {
        $subscribers = $this->subscribers();

        if ($audience !== Newsletter::AUDIENCE_PLUGIN || $plugin === null) {
            return $subscribers->orderBy('email')->get();
        }

        $owners = $this->ownersByPlugin($subscribers->pluck('id'))[$plugin->id] ?? [];

        return User::query()
            ->whereIn('id', $owners)
            ->orderBy('email')
            ->get();
    }

    /**
     * The opted-in shoppers behind each plugin, read from the cart snapshots
     * of the charges the gateway settled, keyed by plugin id.
     *
     * @param  Collection<int, int>  $subscriberIds
     * @return array<int, list<int>>
     */
    private function ownersByPlugin(Collection $subscriberIds): array
    {
        if ($subscriberIds->isEmpty()) {
            return [];
        }

        $owners = [];

        $payments = Payment::query()
            ->successful()
            ->whereIn('user_id', $subscriberIds)
            ->get(['user_id', 'items']);

        foreach ($payments as $payment) {
            foreach ($payment->items ?? [] as $item) {
                $pluginId = (int) ($item['plugin_id'] ?? 0);

                if ($pluginId > 0) {
                    $owners[$pluginId][$payment->user_id] = true;
                }
            }
        }

        return array_map(fn (array $shoppers): array => array_keys($shoppers), $owners);
    }

    /**
     * The validated attributes behind a new row in the sent list.
     *
     * @return array<string, mixed>
     */
    private function attributes(NewsletterRequest $request, ?Plugin $plugin, int $recipients): array
    {
        return [
            'subject' => trim((string) $request->input('subject')),
            'message' => str_replace("\r\n", "\n", trim((string) $request->input('message'))),
            'audience' => (string) $request->input('audience'),
            'plugin_id' => $plugin?->id,
            'download_url' => $request->filled('download_url') ? trim((string) $request->input('download_url')) : null,
            'recipients_count' => $recipients,
            'sent_by' => $request->user()->id,
        ];
    }

    /**
     * Send one copy of the update to every recipient.
     *
     * @param  Collection<int, User>  $recipients
     */
    private function deliver(Collection $recipients, Newsletter $newsletter): void
    {
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(
                new NewsletterMail($newsletter->subject, $newsletter->message, $newsletter->download_url)
            );
        }
    }

    /**
     * Why nothing could be sent to this audience.
     */
    private function nobodyMessage(?Plugin $plugin): string
    {
        if ($plugin !== null) {
            return "No subscribers own {$plugin->name} yet, so there is nobody to send to.";
        }

        return 'Nobody has subscribed to the newsletter yet, so there is nobody to send to.';
    }

    /**
     * The line under the form saying how many shoppers the send reaches.
     */
    private function countNote(int $subscribers): string
    {
        if ($subscribers === 0) {
            return 'Nobody has subscribed to the newsletter yet.';
        }

        return $subscribers.' subscribed '.Str::plural('customer', $subscribers).' will get this.';
    }
}
