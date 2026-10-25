<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [];
        foreach (['astrology' => 'Astrology', 'ebook' => 'Ebook', 'booking' => 'Booking'] as $slug => $name) {
            $cats[$slug] = Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // Placeholder resolves to /images/ph_product.png via asset('storage/...')
        $img = '../images/ph_product.png';

        $products = [
            ['Zodiac Sign Compatibility E-Book', 'zodiac-compatibility-e-books', 'ebook', 'digital', 35, 30, 'Who is your soulmate, and who is your mistake? Find out in your sign\'s guide.'],
            ['Birth Chart Reading E-Book', 'birth-chart-reading-e-book', 'ebook', 'digital', 40, 25, 'A done-for-you breakdown of your sun, moon and rising placements.'],
            ['Love & Relationships Chart Guide', 'love-relationships-chart-guide', 'ebook', 'digital', 30, null, 'Venus, Mars and your 7th house explained in plain language.'],
            ['Astrocartography 1 Dominant Location + Surrounding Areas', 'astrocartography-1-location', 'astrology', 'simple', 35, 30, 'One location plus surrounding areas for Love, Money, or Personal Growth.'],
            ['Astrocartography 2 Dominant Locations + Surrounding Areas', 'astrocartography-2-locations', 'astrology', 'simple', 70, 60, 'Two locations compared to show which supports you more.'],
            ['Astrocartography 3 Dominant Locations + Surrounding Areas', 'astrocartography-3-locations', 'astrology', 'simple', 120, 90, 'Three locations ranked for Love, Money, or Personal Growth.'],
            ['Video Response Reading', 'video-response-reading', 'astrology', 'simple', 50, null, 'Ask one question and get a personal recorded video answer.'],
            ['Live Phone Call Reading (30 min)', 'phone-call-reading-30', 'booking', 'booking', 120, 99, 'A live one-on-one phone session with Mari.'],
            ['Live Phone Call Reading (60 min)', 'phone-call-reading-60', 'booking', 'booking', 220, 180, 'An in-depth hour-long live session covering your full chart.'],
        ];

        foreach ($products as [$name, $slug, $cat, $type, $price, $sale, $short]) {
            Product::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'category_id' => $cats[$cat]->id,
                'type' => $type,
                'price' => $price,
                'sale_price' => $sale,
                'short_description' => $short,
                'description' => $short,
                'image1_path' => $img,
                'status' => 'published',
            ]);
        }
    }
}
