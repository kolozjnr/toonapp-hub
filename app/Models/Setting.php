<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    /**
     * The keys behind the Contact details panel on the admin settings page.
     *
     * @var list<string>
     */
    public const CONTACT_KEYS = [
        'support_email',
        'whatsapp',
        'instagram_url',
        'youtube_url',
        'support_hours',
    ];

    /**
     * The keys behind the Email sending panel on the admin settings page.
     *
     * @var list<string>
     */
    public const EMAIL_KEYS = [
        'emailjs_public_key',
        'emailjs_service_id',
        'template_verify',
        'template_reset',
        'template_purchase',
    ];

    /**
     * Every saved setting keyed by its name, with nulls for the keys that
     * have never been written so panels can bind them straight to a form.
     *
     * @param  list<string>|null  $keys
     * @return array<string, string|null>
     */
    public static function values(?array $keys = null): array
    {
        $saved = static::query()->pluck('value', 'key')->all();

        if ($keys === null) {
            return $saved;
        }

        $requested = [];

        foreach ($keys as $key) {
            $requested[$key] = $saved[$key] ?? null;
        }

        return $requested;
    }

    /**
     * Write the given key/value pairs in one query, keeping keys that were
     * not part of the request untouched.
     *
     * @param  array<string, string|null>  $values
     */
    public static function saveMany(array $values): void
    {
        if ($values === []) {
            return;
        }

        $now = now();

        DB::table((new static)->getTable())->upsert(
            collect($values)
                ->map(fn (?string $value, string $key): array => [
                    'key' => $key,
                    'value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->values()
                ->all(),
            ['key'],
            ['value', 'updated_at'],
        );
    }
}
