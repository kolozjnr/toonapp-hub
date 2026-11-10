<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'first_name', 'last_name', 'email', 'country', 'newsletter', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The staff accounts that never show up as customers.
     *
     * @var list<string>
     */
    public const STAFF_ROLES = ['admin', 'superadmin'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'newsletter' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * The checkouts this shopper started, settled or not.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * The shoppers who can buy from the store: every registered account that
     * is not one of the staff logins.
     */
    public function scopeShoppers(Builder $query): void
    {
        $query->whereDoesntHave('roles', fn (Builder $query): Builder => $query->whereIn('name', self::STAFF_ROLES));
    }
}
