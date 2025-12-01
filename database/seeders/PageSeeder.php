<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'about_us'],
            [
                'title' => 'About Us',
                'content' => '<h1>About Us</h1><p>Welcome to our website. We are dedicated to providing the best mobile phone information.</p>',
                'is_active' => true,
                'meta_title' => 'About Us - PhoneBD',
                'meta_description' => 'Learn more about PhoneBD and our mission.',
            ]
        );
    }
}
