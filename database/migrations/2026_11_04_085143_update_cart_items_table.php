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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->string('cart_token', 100)->nullable()->after('user_id')->index();
            $table->foreignId('plugin_id')->after('cart_token')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'plugin_id']);
            $table->unique(['cart_token', 'plugin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'cart_token', 'plugin_id']);
        });
    }
};
