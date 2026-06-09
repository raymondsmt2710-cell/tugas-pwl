<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HowItWorksSeeder extends Seeder
{
    public function run(): void
    {
        $howItWorks = [
            // Galang Dana
            [
                'type' => 'galang_dana',
                'step_number' => 1,
                'title' => 'Daftar & Verifikasi',
                'description' => 'Buat akun dan lengkapi data diri untuk memulai penggalangan dana yang terpercaya.',
                'icon' => 'fa-solid fa-id-card-clip',
                'color' => 'bg-blue-50 border-blue-100',
                'icon_color' => 'text-blue-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'galang_dana',
                'step_number' => 2,
                'title' => 'Buat Kampanye',
                'description' => 'Isi judul, cerita, target dana, dan unggah foto kampanye Anda dengan mudah.',
                'icon' => 'fa-solid fa-file-pen',
                'color' => 'bg-brand-50 border-brand-100',
                'icon_color' => 'text-brand-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'galang_dana',
                'step_number' => 3,
                'title' => 'Sebarkan',
                'description' => 'Bagikan link kampanye ke media sosial and ajak kerabat untuk ikut mendukung.',
                'icon' => 'fa-solid fa-share-nodes',
                'color' => 'bg-purple-50 border-purple-100',
                'icon_color' => 'text-purple-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'galang_dana',
                'step_number' => 4,
                'title' => 'Cairkan Dana',
                'description' => 'Dana yang terkumpul dapat dicairkan ke rekening bank Anda kapan saja secara transparan.',
                'icon' => 'fa-solid fa-building-columns',
                'color' => 'bg-green-50 border-green-100',
                'icon_color' => 'text-green-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Donasi
            [
                'type' => 'donasi',
                'step_number' => 1,
                'title' => 'Temukan Kampanye',
                'description' => 'Telusuri ratusan kampanye berdasarkan kategori seperti medis, pendidikan, atau bencana.',
                'icon' => 'fa-solid fa-magnifying-glass-dollar',
                'color' => 'bg-blue-50 border-blue-100',
                'icon_color' => 'text-blue-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'donasi',
                'step_number' => 2,
                'title' => 'Pilih & Pastikan',
                'description' => 'Baca cerita kampanye, lihat transparansi dana, dan pastikan kampanye yang ingin Anda dukung.',
                'icon' => 'fa-solid fa-circle-check',
                'color' => 'bg-brand-50 border-brand-100',
                'icon_color' => 'text-brand-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'donasi',
                'step_number' => 3,
                'title' => 'Bayar Aman',
                'description' => 'Donasikan nominal berapapun melalui berbagai metode pembayaran yang aman dan terpercaya.',
                'icon' => 'fa-solid fa-money-bill-wave',
                'color' => 'bg-purple-50 border-purple-100',
                'icon_color' => 'text-purple-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'donasi',
                'step_number' => 4,
                'title' => 'Pantau Dampak',
                'description' => 'Lacak perkembangan kampanye dan lihat bagaimana donasi Anda memberikan dampak nyata.',
                'icon' => 'fa-solid fa-chart-line',
                'color' => 'bg-green-50 border-green-100',
                'icon_color' => 'text-green-500',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('how_it_works')->insert($howItWorks);
    }
}
