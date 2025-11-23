<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Category::create([
            'name' => 'Astrology',
            'slug' => 'astrology',
        ]);
        
        Category::create([
            'name' => 'Ebook',
            'slug' => 'ebook',
        ]);

        Category::create([
            'name' => 'Booking',
            'slug' => 'booking',
        ]);


    }
}
