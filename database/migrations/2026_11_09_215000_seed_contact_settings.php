<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The contact details the store has always shown, moved into settings so
     * the admin can edit or hide each one from the settings page.
     *
     * @var array<string, string>
     */
    private const DEFAULTS = [
        'support_email' => 'support@tooabihub.com',
        'whatsapp' => '+234 801 234 5678',
        'instagram_url' => 'https://instagram.com/toonapphub',
        'youtube_url' => 'https://youtube.com/@toonapphub',
        'support_hours' => 'Mon–Fri, 9am–6pm (WAT)',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::table('settings')->exists()) {
            return;
        }

        $now = now();

        foreach (self::DEFAULTS as $key => $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', array_keys(self::DEFAULTS))->delete();
    }
};
