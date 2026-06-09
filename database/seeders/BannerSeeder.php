<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Harapan baru dimulai dari kepedulian Anda.',
                'subtitle' => 'Bantu sesama melewati masa sulit. Mulai penggalangan dana medis, pendidikan, dan bencana alam secara transparan bersama Autopahala.',
                'image_path' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'Mulai Galang Dana',
                'button_link' => '/campaigns/create',
                'order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bersama Kita Bisa Membantu Sesama',
                'subtitle' => 'Kebaikan sekecil apapun akan memberikan dampak yang luar biasa bagi mereka yang membutuhkan.',
                'image_path' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'Jelajahi Kampanye',
                'button_link' => '/campaigns',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Transparansi Donasi Terjamin 100%',
                'subtitle' => 'Setiap dana yang masuk dan dicairkan tercatat secara detail dan dapat dipantau kapan saja.',
                'image_path' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'Lihat Leaderboard',
                'button_link' => '/leaderboard',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Ulurkan Tangan untuk Pendidikan Anak',
                'subtitle' => 'Bantu anak-anak kurang mampu mendapatkan fasilitas belajar dan sekolah yang layak.',
                'image_path' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'Donasi Sekarang',
                'button_link' => '/campaigns?category=2',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bantuan Medis Cepat dan Tepat',
                'subtitle' => 'Salurkan bantuan dana medis darurat bagi pasien yang membutuhkan penanganan segera.',
                'image_path' => 'https://images.unsplash.com/photo-1578496479914-7ef3b0193be3?auto=format&fit=crop&q=80&w=1600',
                'button_text' => 'Jelajahi Kampanye',
                'button_link' => '/campaigns?category=1',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('banners')->insert($banners);
    }
}
