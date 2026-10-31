<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'tagline',
    'description',
    'features',
    'youtube_url',
    'cover_image',
    'gallery',
    'price',
    'old_price',
    'category',
    'badge',
    'hosts',
    'version',
    'requirements',
    'download_url',
    'is_live',
    'created_by',
])]
class Plugin extends Model
{
    use HasFactory;

    /**
     * Store categories available to admins.
     *
     * @var list<string>
     */
    public const CATEGORIES = ['Plugin', 'Script', 'Preset pack', 'Template', 'Extension'];

    /**
     * Badges that can be shown on a plugin.
     *
     * @var list<string>
     */
    public const BADGES = ['New', 'Bestseller', 'Sale', 'Free'];

    /**
     * Host applications a plugin can support.
     *
     * @var array<string, string>
     */
    public const HOSTS = [
        'AE' => 'After Effects',
        'PR' => 'Premiere Pro',
        'DR' => 'DaVinci Resolve',
        'C4D' => 'Cinema 4D',
        'BL' => 'Blender',
        'FCP' => 'Final Cut Pro',
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'price' => 0,
        'category' => 'Plugin',
        'is_live' => true,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'hosts' => 'array',
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'is_live' => 'boolean',
        ];
    }

    /**
     * Give every plugin a unique slug when it is first created.
     */
    protected static function booted(): void
    {
        static::creating(function (Plugin $plugin): void {
            $plugin->slug = $plugin->slug ?: static::uniqueSlug($plugin->name);
        });
    }

    /**
     * Build a slug for the given name that is not already in use.
     */
    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'plugin';
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * The public URL for the uploaded cover image, if there is one.
     */
    public function coverUrl(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }

    /**
     * The host application tags stored on the plugin.
     *
     * @return list<array{code: string, name: string}>
     */
    public function hostTags(): array
    {
        return collect($this->hosts ?? [])
            ->filter(fn ($code) => is_string($code) && isset(self::HOSTS[$code]))
            ->map(fn (string $code) => ['code' => $code, 'name' => self::HOSTS[$code]])
            ->values()
            ->all();
    }

    /**
     * The price formatted for the store shelf.
     */
    public function priceLabel(): string
    {
        return $this->formatMoney((float) $this->price) ?? 'Free';
    }

    /**
     * The old price formatted for the store shelf, when the plugin is on sale.
     */
    public function oldPriceLabel(): ?string
    {
        $old = $this->old_price !== null ? (float) $this->old_price : 0;

        return $old > (float) $this->price ? $this->formatMoney($old) : null;
    }

    /**
     * The badge shown on the card, defaulting to "Free" for free plugins.
     */
    public function badgeLabel(): ?string
    {
        return $this->badge ?: ((float) $this->price <= 0 ? 'Free' : null);
    }

    /**
     * Two initials used when a plugin has no cover image.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->squish()
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    /**
     * A stable hue for the generated cover placeholder.
     */
    public function coverHue(): int
    {
        return abs(crc32($this->name)) % 360;
    }

    /**
     * Format a price without unnecessary trailing zeroes.
     */
    private function formatMoney(float $amount): ?string
    {
        if ($amount <= 0) {
            return null;
        }

        return '$'.number_format($amount, fmod($amount, 1.0) > 0 ? 2 : 0);
    }

    /**
     * The administrator who uploaded the plugin.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Limit the query to plugins that are visible in the store.
     */
    public function scopeLive(Builder $query): void
    {
        $query->where('is_live', true);
    }
}
