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
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}