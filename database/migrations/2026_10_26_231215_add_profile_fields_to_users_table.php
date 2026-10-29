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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 40)->nullable()->after('name');
            $table->string('last_name', 40)->nullable()->after('first_name');
            $table->string('country', 60)->nullable()->after('email');
            $table->boolean('newsletter')->default(false)->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'country', 'newsletter']);
        });
    }
};
