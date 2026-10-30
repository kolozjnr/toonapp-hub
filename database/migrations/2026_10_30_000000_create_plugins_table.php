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
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('slug', 80)->unique();
            $table->string('tagline', 90)->nullable();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->string('youtube_url', 2048)->nullable();
            $table->string('cover_image', 2048)->nullable();
            $table->json('gallery')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('old_price', 8, 2)->nullable();
            $table->string('category', 30)->default('Plugin');
            $table->string('badge', 20)->nullable();
            $table->json('hosts')->nullable();
            $table->string('version', 20)->nullable();
            $table->string('requirements', 60)->nullable();
            $table->string('download_url', 2048)->nullable();
            $table->boolean('is_live')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_live', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
