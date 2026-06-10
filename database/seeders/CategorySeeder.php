<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id_category' => 1,
                'name' => 'Medis & Kesehatan',
                'slug' => 'medis-kesehatan',
                'logo' => 'fa-solid fa-heart-pulse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 2,
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'logo' => 'fa-solid fa-graduation-cap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 3,
                'name' => 'Bencana Alam',
                'slug' => 'bencana-alam',
                'logo' => 'fa-solid fa-house-flood-water',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 4,
                'name' => 'Sosial & Panti',
                'slug' => 'sosial-panti',
                'logo' => 'fa-solid fa-hands-holding-child',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 5,
                'name' => 'Fasilitas Umum',
                'slug' => 'fasilitas-umum',
                'logo' => 'fa-solid fa-circle-dot',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 6,
                'name' => 'Lingkungan',
                'slug' => 'lingkungan',
                'logo' => 'fa-solid fa-leaf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 7,
                'name' => 'Menolong Hewan',
                'slug' => 'menolong-hewan',
                'logo' => 'fa-solid fa-paw',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 8,
                'name' => 'Difabel',
                'slug' => 'difabel',
                'logo' => 'fa-solid fa-wheelchair',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 9,
                'name' => 'Panti Asuhan',
                'slug' => 'panti-asuhan',
                'logo' => 'fa-solid fa-people-roof',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 10,
                'name' => 'Balita & Anak Sakit',
                'slug' => 'balita-anak-sakit',
                'logo' => 'fa-solid fa-baby',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 11,
                'name' => 'Anak-anak',
                'slug' => 'anak-anak',
                'logo' => 'fa-solid fa-child',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 12,
                'name' => 'Program Sosial',
                'slug' => 'program-sosial',
                'logo' => 'fa-solid fa-hand-holding-heart',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}