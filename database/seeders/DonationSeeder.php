<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        // 1. Data Donasi Utama (Sesuai DatabaseSeeder lama)
        $mainDonations = [
            // Donasi untuk Kampanye 1 (Bantu Dek Aisyah)
            [
                'id_campaign' => 1,
                'id_user' => 4, // Budi Santoso
                'donor_name' => 'Budi Santoso',
                'donor_email' => 'budi.santoso@gmail.com',
                'donor_message' => 'Semoga lekas sembuh ya Dek Aisyah, tetap semangat!',
                'donation_amount' => 15000000.00,
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'order_id' => Donation::generateOrderId() . '-M1',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(8),
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'id_campaign' => 1,
                'id_user' => 5, // Sarah Amalia
                'donor_name' => 'Sarah Amalia',
                'donor_email' => 'sarah.amalia@gmail.com',
                'donor_message' => 'Lekas pulih Dek Aisyah manis. Doa kami bersamamu.',
                'donation_amount' => 20000000.00,
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'order_id' => Donation::generateOrderId() . '-M2',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(7),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'id_campaign' => 1,
                'id_user' => null,
                'donor_name' => 'Hamba Allah',
                'donor_email' => 'hamba.allah@gmail.com',
                'donor_message' => 'Semoga berkah dan bermanfaat bagi kesembuhan adik kita.',
                'donation_amount' => 16200000.00,
                'payment_status' => 'paid',
                'payment_method' => 'gopay',
                'order_id' => Donation::generateOrderId() . '-M3',
                'is_anonymous' => true,
                'paid_at' => now()->subDays(6),
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],

            // Donasi untuk Kampanye 2 (Jembatan Sukamaju)
            [
                'id_campaign' => 2,
                'id_user' => 4, // Budi Santoso
                'donor_name' => 'Budi Santoso',
                'donor_email' => 'budi.santoso@gmail.com',
                'donor_message' => 'Semoga jembatan baru ini memperlancar sekolah anak-anak kita.',
                'donation_amount' => 25000000.00,
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'order_id' => Donation::generateOrderId() . '-M4',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(4),
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'id_campaign' => 2,
                'id_user' => 5, // Sarah Amalia
                'donor_name' => 'Sarah Amalia',
                'donor_email' => 'sarah.amalia@gmail.com',
                'donor_message' => 'Luar biasa aksi gotong royong warga Sukamaju. Sukses terus!',
                'donation_amount' => 15000000.00,
                'payment_status' => 'paid',
                'payment_method' => 'shopeepay',
                'order_id' => Donation::generateOrderId() . '-M5',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id_campaign' => 2,
                'id_user' => null,
                'donor_name' => 'Keluarga Dermawan',
                'donor_email' => 'dermawan@gmail.com',
                'donor_message' => 'Titip donasi untuk perbaikan akses jalan warga.',
                'donation_amount' => 8000000.00,
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'order_id' => Donation::generateOrderId() . '-M6',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],

            // Donasi untuk Kampanye 3 (Beasiswa Yatim)
            [
                'id_campaign' => 3,
                'id_user' => 4, // Budi Santoso
                'donor_name' => 'Budi Santoso',
                'donor_email' => 'budi.santoso@gmail.com',
                'donor_message' => 'Semoga adik-adik yatim piatu bisa terus rajin belajar dan berprestasi.',
                'donation_amount' => 10000000.00,
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'order_id' => Donation::generateOrderId() . '-M7',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(12),
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'id_campaign' => 3,
                'id_user' => 5, // Sarah Amalia
                'donor_name' => 'Sarah Amalia',
                'donor_email' => 'sarah.amalia@gmail.com',
                'donor_message' => 'Pendidikan adalah kunci masa depan. Sukses program beasiswanya.',
                'donation_amount' => 28250000.00,
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'order_id' => Donation::generateOrderId() . '-M8',
                'is_anonymous' => false,
                'paid_at' => now()->subDays(11),
                'created_at' => now()->subDays(11),
                'updated_at' => now()->subDays(11),
            ],
        ];

        foreach ($mainDonations as $don) {
            Donation::create($don);
        }

        // Ambil data User untuk mengaitkan donatur
        $users = User::all();
        $campaigns = Campaign::all();

        // Beberapa doa khas Indonesia untuk donasi yang bervariasi dan menyentuh
        $indonesianPrayers = [
            'Semoga berkah dan bermanfaat untuk kelancaran program ini.',
            'Lekas sembuh dan diangkat penyakitnya oleh Tuhan YME, aamiin.',
            'Semoga sedikit bantuan ini bisa meringankan beban saudara-saudara kita.',
            'Sukses terus untuk para relawan di lapangan yang luar biasa.',
            'Semoga donasi ini berkah untuk kita semua dan dilancarkan urusannya.',
            'Bismillah, titip doa untuk kesembuhan adik kecil yang manis.',
            'Semoga pembangunan sarana ini cepat rampung dan bisa segera digunakan.',
            'Semoga adik-adik yatim piatu tetap semangat belajar dan meraih mimpi!',
            'Menyalurkan sebagian rezeki, semoga bermanfaat bagi sesama.',
            'Tetap semangat berjuang melawan masa-masa sulit ini.',
        ];

        // 2. Generate 130 Donasi Dummy menggunakan Faker
        for ($i = 1; $i <= 130; $i++) {
            $campaign = $campaigns->random();
            $userId = $faker->boolean(60) ? $users->random()->id_user : null; // 60% donasi dari user terdaftar

            $donorName = '';
            $donorEmail = '';
            
            if ($userId) {
                $user = $users->firstWhere('id_user', $userId);
                $donorName = $user->full_name;
                $donorEmail = $user->email;
            } else {
                $donorName = $faker->name();
                $donorEmail = $faker->unique()->safeEmail();
            }

            // Peluang donasi bersifat anonim (misal: 25%)
            $isAnonymous = $faker->boolean(25);

            // Tentukan status pembayaran
            $paymentStatus = $faker->randomElement(['paid', 'paid', 'paid', 'paid', 'pending', 'failed', 'expired']);
            $paymentMethod = null;
            $paidAt = null;

            if ($paymentStatus === 'paid') {
                $paymentMethod = $faker->randomElement(['credit_card', 'bank_transfer', 'gopay', 'qris', 'shopeepay']);
                $paidAt = now()->subDays(rand(1, 25))->subHours(rand(1, 23));
            }

            // Jumlah nominal kelipatan 10.000 atau 50.000
            $donationAmount = $faker->randomElement([10000, 20000, 50000, 100000, 250000, 500000, 1000000, 1500000]);
            
            // Batasi donasi minimal sesuai dengan ketentuan kampanye
            if ($donationAmount < $campaign->minimum_donation) {
                $donationAmount = $campaign->minimum_donation;
            }

            $hasMessage = $faker->boolean(70);
            $donorMessage = $hasMessage ? $faker->randomElement($indonesianPrayers) : null;

            Donation::create([
                'id_campaign' => $campaign->id_campaign,
                'id_user' => $userId,
                'donor_name' => $donorName,
                'donor_email' => $donorEmail,
                'donor_message' => $donorMessage,
                'donation_amount' => $donationAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'payment_token' => $paymentStatus === 'pending' ? 'snap-tok-' . Str::random(20) : null,
                'order_id' => Donation::generateOrderId() . '-' . $i,
                'is_anonymous' => $isAnonymous,
                'paid_at' => $paidAt,
                'created_at' => $paidAt ?? now()->subDays(rand(1, 20)),
                'updated_at' => now(),
            ]);
        }

        // 3. AUTO-UPDATE Collected Amount & Available Balance pada Campaigns
        foreach ($campaigns as $camp) {
            // Hitung sum dari seluruh donasi sukses
            $totalCollected = Donation::where('id_campaign', $camp->id_campaign)
                ->where('payment_status', 'paid')
                ->sum('donation_amount');

            // Hitung sisa saldo setelah dikurangi dana yang ditarik (jika ada)
            $available = $totalCollected - $camp->withdrawal_amount;
            if ($available < 0) {
                $available = 0;
            }

            // Update record kampanye
            $camp->update([
                'collected_amount' => $totalCollected,
                'available_balance' => $available,
            ]);
        }
    }
}
