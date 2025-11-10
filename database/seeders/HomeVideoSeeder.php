<?php

namespace Database\Seeders;

use App\Models\HomeVideo;
use Illuminate\Database\Seeder;

class HomeVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeVideo::create([
            'title' => 'Welcome Message',
            'description' => 'A message from Hon. Fatimatu Abubakar about our mission and vision for the community.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Replace with actual video URL
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }
}
