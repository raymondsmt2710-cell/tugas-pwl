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
        DB::table('banners')->truncate();
        DB::table('faqs')->truncate();
        DB::table('how_it_works')->truncate();
        DB::table('site_settings')->truncate();

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

        // 5. SEED DATA BANNERS
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

        // 6. SEED DATA FAQS
        $faqs = [
            [
                'question' => 'Bagaimana cara membuat kampanye penggalangan dana di Autopahala?',
                'answer' => 'Untuk memulai, Anda perlu mendaftarkan akun terlebih dahulu. Setelah berhasil masuk ke akun Anda, klik tombol "Mulai Galang Dana" yang terletak di halaman navigasi atau di dalam dashboard Anda. Selanjutnya, lengkapi formulir informasi kampanye seperti judul, target dana, kategori yang sesuai, foto/banner pendukung, serta cerita kronologi lengkap mengenai penerima manfaat. Setelah dikirim, tim verifikasi kami akan meninjau kelayakan kampanye Anda dalam waktu maksimal 1x24 jam sebelum diaktifkan secara publik.',
                'order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Berapa biaya administrasi yang dikenakan oleh platform?',
                'answer' => 'Autopahala berkomitmen untuk menyalurkan kebaikan secara maksimal tanpa potongan tersembunyi. Kami hanya mengenakan biaya operasional sistem sebesar 5% khusus untuk kampanye kategori umum guna menunjang pemeliharaan server dan biaya gerbang pembayaran (payment gateway). Sedangkan khusus untuk kampanye kategori tanggap darurat, medis kemanusiaan kritis, dan bencana alam nasional, kami mengenakan potongan 0% alias sepenuhnya gratis tanpa biaya administrasi apa pun.',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana proses verifikasi untuk kampanye yang diajukan?',
                'answer' => 'Keamanan dan transparansi adalah prioritas utama kami. Setiap kampanye yang diajukan akan melalui proses verifikasi oleh tim kami. Penggalang dana wajib mengunggah dokumen identitas diri (KTP) serta dokumen pendukung atau bukti resmi yang relevan dengan tujuan penggalangan dana tersebut. Hal ini kami lakukan demi menjamin keaslian kampanye dan menghindari segala bentuk tindak penipuan.',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana cara mencairkan dana yang telah terkumpul?',
                'answer' => 'Dana yang masuk dan berstatus berhasil (paid) dapat Anda pantau setiap saat secara transparan di dashboard Anda. Untuk mencairkannya, penggalang dana dapat mengajukan pencairan berkala melalui menu "Tarik Dana" dengan mengunggah Rencana Anggaran Biaya (RAB) serta rincian penggunaan kebutuhan mendesak terbaru. Setelah diajukan, dana akan ditransfer langsung ke rekening bank terdaftar yang telah divalidasi oleh tim keuangan kami dalam waktu 2-3 hari kerja.',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apakah saya bisa memberikan donasi secara anonim?',
                'answer' => 'Ya, Anda tentu bisa memberikan donasi secara anonim. Saat Anda mengisi formulir pembayaran donasi di kampanye mana pun, Anda cukup mengosongkan kolom nama donatur atau menuliskan nama samaran (seperti Hamba Allah). Sistem kami tidak akan menampilkan identitas asli Anda secara publik di halaman detail kampanye maupun di papan peringkat donatur teratas (Leaderboard), namun laporan penyaluran dana tetap tercatat sah secara akuntansi di sistem internal.',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('faqs')->insert($faqs);

        // 7. SEED DATA HOW IT WORKS
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
                'description' => 'Bagikan link kampanye ke media sosial dan ajak kerabat untuk ikut mendukung.',
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

        // 8. SEED DATA SITE SETTINGS
        $siteSettings = [
            [
                'id' => 1,
                'site_name' => 'Autopahala',
                'email' => 'support@autopahala.com',
                'phone' => '+628123456789',
                'address' => 'Medan, Sumatera Utara, Indonesia',
                'social_media' => json_encode([
                    ['platform' => 'instagram', 'label' => '@autopahala', 'url' => 'https://instagram.com/autopahala'],
                    ['platform' => 'facebook', 'label' => 'AutoPahala Fans', 'url' => 'https://facebook.com/autopahala'],
                    ['platform' => 'twitter', 'label' => '@autopahala', 'url' => 'https://twitter.com/autopahala']
                ]),
                'footer_text' => 'Autopahala. All rights reserved.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('site_settings')->insert($siteSettings);
    }
}