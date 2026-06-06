<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kesehatan', 'slug' => 'kesehatan'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan'],
            ['name' => 'Bencana Alam', 'slug' => 'bencana-alam'],
            ['name' => 'Anak-anak', 'slug' => 'anak-anak'],
            ['name' => 'Program Sosial', 'slug' => 'program-sosial'],
            ['name' => 'Lingkungan', 'slug' => 'lingkungan'],
            ['name' => 'Menolong Hewan', 'slug' => 'menolong-hewan'],
            ['name' => 'Difabel', 'slug' => 'difabel'],
            ['name' => 'Panti Asuhan', 'slug' => 'panti-asuhan'],
            ['name' => 'Balita & Anak Sakit', 'slug' => 'balita-anak-sakit'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}