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
