<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['user_id', 'cart_token', 'plugin_id'];

    /**
     * The plugin this row points at.
     */
    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }

    /**
     * The owner (null for guest carts).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
