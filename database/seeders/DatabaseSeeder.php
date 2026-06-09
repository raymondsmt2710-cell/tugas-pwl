<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Matikan foreign key checks agar proses pembersihan aman
        Schema::disableForeignKeyConstraints();

        // 2. Bersihkan seluruh tabel terkait agar data tidak bertumpuk double
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

        // 3. Panggil masing-masing seeder secara berurutan sesuai ketergantungan relasi
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            CampaignSeeder::class,
            DonationSeeder::class,
            WithdrawalSeeder::class,
            BannerSeeder::class,
            FaqSeeder::class,
            HowItWorksSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}