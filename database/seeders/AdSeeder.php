<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing ads to prevent duplicates
        Ad::truncate();

        // 1. Header Ad (AdSense Script)
        Ad::create([
            'title' => 'Header AdSense',
            'type' => 'script',
            'position' => 'header_top',
            'script' => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX" crossorigin="anonymous"></script>',
            'is_active' => true,
        ]);

        // 2. Sidebar Ad (Image Banner)
        Ad::create([
            'title' => 'Sidebar Promotion',
            'type' => 'image',
            'position' => 'sidebar_right',
            'image' => 'ads/sidebar-promo.jpg', // Placeholder path
            'link' => 'https://example.com/promo',
            'is_active' => true,
        ]);

        // 3. Article Inline Ad (AdSense) - Renamed/Used as generic or specific
        Ad::create([
            'title' => 'Article Inline Ad',
            'type' => 'script',
            'position' => 'article_inline',
            'script' => '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="XXXXXXXXXX" data-ad-format="auto" data-full-width-responsive="true"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>',
            'is_active' => true,
        ]);

        // 4. Product Page: Below Hero
        Ad::create([
            'title' => 'Product Below Hero',
            'type' => 'script',
            'position' => 'product_below_hero',
            'script' => '<div style="background:#f0f0f0;padding:20px;text-align:center;border:1px dashed #ccc;color:#999;font-size:12px;">Advertisement</div>',
            'is_active' => true,
        ]);

        // 5. Product Page: Below Specs
        Ad::create([
            'title' => 'Product Below Specs',
            'type' => 'script',
            'position' => 'product_below_specs',
            'script' => '<div style="background:#f0f0f0;padding:20px;text-align:center;border:1px dashed #ccc;color:#999;font-size:12px;">Advertisement</div>',
            'is_active' => true,
        ]);

        // 6. Product Page: Below FAQ
        Ad::create([
            'title' => 'Product Below FAQ',
            'type' => 'script',
            'position' => 'product_below_faq',
            'script' => '<div style="background:#f0f0f0;padding:20px;text-align:center;border:1px dashed #ccc;color:#999;font-size:12px;">Advertisement</div>',
            'is_active' => true,
        ]);

        // 7. Sidebar Middle
        Ad::create([
            'title' => 'Sidebar Middle',
            'type' => 'image',
            'position' => 'sidebar_middle',
            'image' => 'ads/sidebar-promo-2.jpg',
            'link' => '#',
            'is_active' => true,
        ]);

        // 8. Sidebar Bottom
        Ad::create([
            'title' => 'Sidebar Bottom',
            'type' => 'script',
            'position' => 'sidebar_bottom',
            'script' => '<div style="background:#f0f0f0;padding:20px;text-align:center;border:1px dashed #ccc;color:#999;font-size:12px;">Advertisement</div>',
            'is_active' => true,
        ]);

        // 9. Footer Ad (Custom Code/Script)
        Ad::create([
            'title' => 'Footer Sticky Ad',
            'type' => 'code',
            'position' => 'footer_sticky',
            'script' => '<div style="position:fixed;bottom:0;width:100%;height:50px;background:red;">Sticky Ad</div>',
            'is_active' => false, // Disabled by default
        ]);
    }
}
