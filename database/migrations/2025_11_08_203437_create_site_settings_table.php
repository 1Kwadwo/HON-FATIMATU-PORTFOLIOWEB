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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default foundation settings
        DB::table('site_settings')->insert([
            ['key' => 'foundation_name', 'value' => 'Grassroot Foundation', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'foundation_url', 'value' => 'https://grassrootfoundation.org', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'foundation_description', 'value' => 'Supporting communities through education, healthcare, and empowerment programs.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'foundation_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
