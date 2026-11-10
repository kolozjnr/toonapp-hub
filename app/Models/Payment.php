<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * Payment states a record moves through, in order.
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    /**
     * The gateway this record was created for.
     */
    public const GATEWAY_PAYSTACK = 'paystack';

    /**
     * The statuses that mean the shopper was actually charged.
     *
     * @var list<string>
     */
    public const SUCCESS_STATUSES = [self::STATUS_SUCCESS, 'successful', 'paid'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'reference',
        'idempotency_key',
        'cart_hash',
        'amount',
        'currency',
        'gateway',
        'status',
        'items',
        'authorization_url',
        'gateway_payload',
        'failure_reason',
        'verified_at',
        'paid_at',
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'gateway' => self::GATEWAY_PAYSTACK,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'items' => 'array',
            'gateway_payload' => 'array',
            'verified_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * The customer who initiated the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Limit the query to payments that have not settled yet.
     */
    public function scopePending(Builder $query): void
    {
        $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Limit the query to payments the gateway confirmed as charged.
     */
    public function scopeSuccessful(Builder $query): void
    {
        $query->whereIn('status', self::SUCCESS_STATUSES);
    }

    /**
     * Whether the shopper has actually been charged.
     */
    public function isSuccessful(): bool
    {
        return in_array($this->status, self::SUCCESS_STATUSES, true);
    }

    /**
     * Whether the gateway reported a definitive failure.
     */
    public function hasFailed(): bool
    {
        return in_array($this->status, [self::STATUS_FAILED, 'abandoned', 'reverted'], true);
    }

    /**
     * Whether Paystack already knows about a charge for this payment, so a
     * second attempt must never be started for it.
     */
    public function hasChargeInFlight(): bool
    {
        $gatewayStatus = strtolower((string) ($this->gateway_payload['verification']['status'] ?? ''));

        if ($gatewayStatus === '') {
            return false;
        }

        return in_array($gatewayStatus, ['success', 'ongoing', 'pending', 'queued', 'started', 'processing'], true);
    }

    /**
     * The cart total the amount was taken from, recalculated from the plugin
     * snapshot so it can be compared against what the gateway reports.
     */
    public function expectedAmount(): int
    {
        return (int) collect($this->items)->sum(fn (array $item): float => round(((float) $item['price']) * 100));
    }

    /**
     * The amount formatted for display, e.g. "25.00 NGN".
     */
    public function amountLabel(): string
    {
        return self::formatAmount($this->amount, $this->currency);
    }

    /**
     * Any total held in the smallest currency unit formatted for display,
     * e.g. "25.00 NGN".
     */
    public static function formatAmount(int $amount, string $currency): string
    {
        return number_format($amount / 100, 2, '.', ',').' '.$currency;
    }
}
