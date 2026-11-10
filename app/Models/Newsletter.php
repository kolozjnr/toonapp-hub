<?php

namespace App\Models;

use Database\Factories\NewsletterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'subject',
    'message',
    'audience',
    'plugin_id',
    'download_url',
    'recipients_count',
    'sent_by',
])]
class Newsletter extends Model
{
    /** @use HasFactory<NewsletterFactory> */
    use HasFactory;

    /**
     * The audience values an admin picks from when writing an update.
     */
    public const AUDIENCE_SUBSCRIBERS = 'subscribers';

    public const AUDIENCE_PLUGIN = 'plugin';

    /**
     * The audience values with the label shown on the form.
     *
     * @var array<string, string>
     */
    public const AUDIENCE_LABELS = [
        self::AUDIENCE_SUBSCRIBERS => 'Everyone subscribed',
        self::AUDIENCE_PLUGIN => 'Owners of a plugin',
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'audience' => self::AUDIENCE_SUBSCRIBERS,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recipients_count' => 'integer',
        ];
    }

    /**
     * The plugin this update is about, when it went to the shoppers who own it.
     */
    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }

    /**
     * The administrator who wrote it.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * How the sent list describes who this went to.
     */
    public function audienceLabel(): string
    {
        if ($this->audience !== self::AUDIENCE_PLUGIN) {
            return self::AUDIENCE_LABELS[self::AUDIENCE_SUBSCRIBERS];
        }

        return $this->plugin ? 'Owners of '.$this->plugin->name : 'Owners of a removed plugin';
    }

    /**
     * How many shoppers the send went to, e.g. "5 subscribers".
     */
    public function recipientsLabel(): string
    {
        return $this->recipients_count.' '.Str::plural('subscriber', $this->recipients_count);
    }
}
