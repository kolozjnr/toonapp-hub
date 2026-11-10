<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference', 64)->unique();
            $table->string('idempotency_key', 64)->unique();
            $table->string('cart_hash', 64)->index();
            $table->unsignedBigInteger('amount')->comment('Total in the smallest currency unit, taken from the cart when Pay now was clicked.');
            $table->string('currency', 3);
            $table->string('gateway', 32)->default('paystack');
            $table->string('status', 32)->default('pending')->index();
            $table->json('items')->comment('Snapshot of the plugins and prices the amount was calculated from.');
            $table->text('authorization_url')->nullable();
            $table->json('gateway_payload')->nullable()->comment('Last raw response from Paystack.');
            $table->string('failure_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
