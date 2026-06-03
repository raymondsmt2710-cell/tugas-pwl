<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AutopahalaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama terlebih dahulu (biar tidak duplikat)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('campaigns')->truncate();
        DB::table('donations')->truncate(); // Sesuaikan jika nama tabel donasimu berbeda
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Insert Data User (Untuk Top Penggalang / Kreator)
        $userIds = [];
        $users = [
            ['name' => 'Andi Wijaya', 'email' => 'andi@gmail.com', 'password' => Hash::make('password')],
            ['name' => 'Siti Rahma', 'email' => 'siti@gmail.com', 'password' => Hash::make('password')],
            ['name' => 'Budi Santoso', 'email' => 'budi@gmail.com', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            $userIds[] = DB::table('users')->insertGetId(array_merge($user, [
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 3. Insert Data Kategori
        $categoryIds = [];
        $categories = ['Medis', 'Pendidikan', 'Bencana Alam', 'Panti Asuhan', 'Kemanusiaan'];

        foreach ($categories as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Insert Data Kampanye (Campaigns)
        $campaignIds = [];
        $campaigns = [
            [
                'title' => 'Solidaritas Pangan: Berbagi Paket Makanan Lansia',
                'description' => 'Program bantuan pangan nutrisi untuk lansia dhuafa yang hidup sebatang kara di daerah pelosok.',
                'target_amount' => 50000000,
                'current_amount' => 30000000,
                'location' => 'Bandung, West Java',
                'banner_image' => null, // null agar otomatis pakai gambar default Unsplash di blade
                'user_id' => $userIds[0],
                'category_id' => $categoryIds[4], // Kemanusiaan
            ],
            [
                'title' => 'Beasiswa Pendidikan Anak Yatim Berprestasi',
                'description' => 'Mari bantu anak-anak yatim melanjutkan sekolah mereka demi meraih masa depan yang lebih cerah.',
                'target_amount' => 30000000,
                'current_amount' => 12000000,
                'location' => 'Jakarta Pusat, DKI Jakarta',
                'banner_image' => null,
                'user_id' => $userIds[1],
                'category_id' => $categoryIds[1], // Pendidikan
            ],
            [
                'title' => 'Bantuan Darurat Korban Kebakaran Pemukiman',
                'description' => 'Dukungan logistik, obat-obatan, dan pakaian layak pakai untuk korban kebakaran pemukiman padat.',
                'target_amount' => 100000000,
                'current_amount' => 90000000,
                'location' => 'Surabaya, East Java',
                'banner_image' => null,
                'user_id' => $userIds[2],
                'category_id' => $categoryIds[2], // Bencana Alam
            ],
        ];

        foreach ($campaigns as $camp) {
            $campaignIds[] = DB::table('campaigns')->insertGetId(array_merge($camp, [
                'slug' => Str::slug($camp['title']),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 5. Insert Data Donasi (Untuk Mengisi Top Donatur & Angka Penyaluran)
        $donations = [
            ['donor_name' => 'Rahmat Hidayat', 'total_amount' => 5000000, 'campaign_id' => $campaignIds[0]],
            ['donor_name' => 'Rahmat Hidayat', 'total_amount' => 2000000, 'campaign_id' => $campaignIds[1]], // Biar total donasinya 2 kali
            ['donor_name' => 'Anisa Putri', 'total_amount' => 15000000, 'campaign_id' => $campaignIds[2]],
            ['donor_name' => 'Hamba Allah', 'total_amount' => 500000, 'campaign_id' => $campaignIds[0]],
        ];

        foreach ($donations as $don) {
            DB::table('donations')->insert(array_merge($don, [
                'status' => 'success', // atau 'paid' / 'completed' sesuai enum databasemu
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}