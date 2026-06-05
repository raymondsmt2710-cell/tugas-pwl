<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menonaktifkan sementara foreign key checks agar proses pembersihan database aman
        Schema::disableForeignKeyConstraints();

        // Bersihkan data lama di tabel terkait agar tidak menumpuk double
        DB::table('donations')->truncate();
        DB::table('withdrawals')->truncate();
        DB::table('campaign_galleries')->truncate();
        DB::table('campaign_documents')->truncate();
        DB::table('campaign_likes')->truncate();
        DB::table('campaign_comments')->truncate();
        DB::table('campaign_reports')->truncate();
        DB::table('campaigns')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        // 1. SEED DATA USERS
        $users = [
            [
                'id_user' => 1,
                'full_name' => 'Yayasan Lentera Bangsa',
                'username' => 'lentera_bangsa',
                'email' => 'lentera.bangsa@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'full_name' => 'Rahmat Hidayat',
                'username' => 'rahmat_hidayat',
                'email' => 'rahmat.hidayat@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'full_name' => 'Komunitas Pemuda Sukamaju',
                'username' => 'pemuda_sukamaju',
                'email' => 'pemuda.sukamaju@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'full_name' => 'Budi Santoso',
                'username' => 'budi_santoso',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 5,
                'full_name' => 'Sarah Amalia',
                'username' => 'sarah_amalia',
                'email' => 'sarah.amalia@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 6,
                'full_name' => 'Admin Autopahala',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);

        // 2. SEED DATA CATEGORIES
        $categories = [
            [
                'id_category' => 1,
                'name' => 'Medis & Kesehatan',
                'slug' => 'medis-kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 2,
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 3,
                'name' => 'Bencana Alam',
                'slug' => 'bencana-alam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 4,
                'name' => 'Sosial & Panti',
                'slug' => 'sosial-panti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 5,
                'name' => 'Fasilitas Umum',
                'slug' => 'fasilitas-umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 6,
                'name' => 'Lingkungan',
                'slug' => 'lingkungan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 7,
                'name' => 'Menolong Hewan',
                'slug' => 'menolong-hewan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 8,
                'name' => 'Difabel',
                'slug' => 'difabel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 9,
                'name' => 'Panti Asuhan',
                'slug' => 'panti-asuhan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 10,
                'name' => 'Balita & Anak Sakit',
                'slug' => 'balita-anak-sakit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 11,
                'name' => 'Anak-anak',
                'slug' => 'anak-anak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_category' => 12,
                'name' => 'Program Sosial',
                'slug' => 'program-sosial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('categories')->insert($categories);

        // 3. SEED DATA CAMPAIGNS
        $campaigns = [
            [
                'id_campaign' => 1,
                'id_user' => 2, // Rahmat Hidayat
                'id_category' => 1, // Medis
                'title' => 'Bantu Dek Aisyah Sembuh dari Kanker Tulang',
                'slug' => Str::slug('Bantu Dek Aisyah Sembuh dari Kanker Tulang'),
                'short_description' => 'Dek Aisyah (7 tahun) berjuang melawan osteosarkoma. Mari bantu biaya kemoterapi.',
                'description' => 'Dek Aisyah didiagnosis menderita kanker tulang ganas sejak akhir tahun lalu. Keluarga kesulitan membiayai kemoterapi mingguan di RS Kanker Nasional karena keterbatasan biaya operasional.',
                'target_amount' => 85000000.00,
                'minimum_donation' => 10000.00,
                'collected_amount' => 51200000.00,
                'withdrawal_amount' => 10000000.00,
                'available_balance' => 41200000.00,
                'banner_image' => null,
                'video_url' => null,
                'campaign_status' => 'active',
                'verification_status' => 'active',
                'status' => 'approved',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(30),
                'created_at' => now()->subDays(10),
                'updated_at' => now(),
            ],
            [
                'id_campaign' => 2,
                'id_user' => 3, // Pemuda Sukamaju
                'id_category' => 5, // Fasilitas Umum
                'title' => 'Bangun Kembali Jembatan Roboh Desa Sukamaju',
                'slug' => Str::slug('Bangun Kembali Jembatan Roboh Desa Sukamaju'),
                'short_description' => 'Jembatan penghubung utama roboh akibat banjir bandang. Anak-anak kesulitan sekolah.',
                'description' => 'Jembatan gantung kayu yang menjadi akses utama anak-anak pergi sekolah dan warga mengangkut hasil tani di Desa Sukamaju terputus total. Warga terpaksa menyeberangi sungai deras.',
                'target_amount' => 120000000.00,
                'minimum_donation' => 15000.00,
                'collected_amount' => 48000000.00,
                'withdrawal_amount' => 0.00,
                'available_balance' => 48000000.00,
                'banner_image' => null,
                'video_url' => null,
                'campaign_status' => 'active',
                'verification_status' => 'active',
                'status' => 'approved',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(45),
                'created_at' => now()->subDays(5),
                'updated_at' => now(),
            ],
            [
                'id_campaign' => 3,
                'id_user' => 1, // Yayasan Lentera Bangsa
                'id_category' => 2, // Pendidikan
                'title' => 'Beasiswa Sekolah & Buku Anak Yatim Piatu',
                'slug' => Str::slug('Beasiswa Sekolah & Buku Anak Yatim Piatu'),
                'short_description' => 'Program pengadaan peralatan sekolah dan beasiswa untuk 80 anak yatim dhuafa.',
                'description' => 'Memasuki tahun ajaran baru, banyak anak yatim dhuafa binaan yayasan kami terancam putus sekolah karena tidak memiliki biaya SPP dan buku pelajaran layak.',
                'target_amount' => 45000000.00,
                'minimum_donation' => 10000.00,
                'collected_amount' => 38250000.00,
                'withdrawal_amount' => 20000000.00,
                'available_balance' => 18250000.00,
                'banner_image' => null,
                'video_url' => null,
                'campaign_status' => 'active',
                'verification_status' => 'active',
                'status' => 'approved',
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(15),
                'created_at' => now()->subDays(15),
                'updated_at' => now(),
            ],
        ];
        DB::table('campaigns')->insert($campaigns);

        // 4. SEED DATA DONATIONS
        $donations = [
            // Donasi untuk Kampanye 1
            [
                'id_campaign' => 1,
                'id_user' => 4, // Budi Santoso
                'donor_name' => 'Budi Santoso',
                'donor_email' => 'budi.santoso@gmail.com',
                'donation_amount' => 15000000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'id_campaign' => 1,
                'id_user' => 5, // Sarah Amalia
                'donor_name' => 'Sarah Amalia',
                'donor_email' => 'sarah.amalia@gmail.com',
                'donation_amount' => 20000000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'id_campaign' => 1,
                'id_user' => null,
                'donor_name' => 'Anonim',
                'donor_email' => 'hamba.allah@gmail.com',
                'donation_amount' => 16200000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],

            // Donasi untuk Kampanye 2
            [
                'id_campaign' => 2,
                'id_user' => 4, // Budi Santoso
                'donor_name' => 'Budi Santoso',
                'donor_email' => 'budi.santoso@gmail.com',
                'donation_amount' => 25000000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'id_campaign' => 2,
                'id_user' => 5, // Sarah Amalia
                'donor_name' => 'Sarah Amalia',
                'donor_email' => 'sarah.amalia@gmail.com',
                'donation_amount' => 15000000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id_campaign' => 2,
                'id_user' => null,
                'donor_name' => 'Keluarga Dermawan',
                'donor_email' => 'dermawan@gmail.com',
                'donation_amount' => 8000000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],

            // Donasi untuk Kampanye 3
            [
                'id_campaign' => 3,
                'id_user' => 4, // Budi Santoso
                'donor_name' => 'Budi Santoso',
                'donor_email' => 'budi.santoso@gmail.com',
                'donation_amount' => 10000000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'id_campaign' => 3,
                'id_user' => 5, // Sarah Amalia
                'donor_name' => 'Sarah Amalia',
                'donor_email' => 'sarah.amalia@gmail.com',
                'donation_amount' => 28250000.00,
                'payment_status' => 'paid',
                'created_at' => now()->subDays(11),
                'updated_at' => now()->subDays(11),
            ],
        ];
        DB::table('donations')->insert($donations);
    }
}