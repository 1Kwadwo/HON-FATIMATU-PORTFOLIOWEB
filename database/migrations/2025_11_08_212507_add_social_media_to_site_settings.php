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
        DB::table('site_settings')->insert([
            ['key' => 'social_facebook', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_twitter', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_instagram', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', ['social_facebook', 'social_twitter', 'social_instagram'])->delete();
    }
};
