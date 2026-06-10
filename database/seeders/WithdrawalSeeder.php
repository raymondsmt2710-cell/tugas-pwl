<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class WithdrawalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        // 1. Data Penarikan Utama (Sesuai dengan withdrawal_amount awal di CampaignSeeder)
        $mainWithdrawals = [
            [
                'id_campaign' => 1, // Bantu Dek Aisyah
                'id_user' => 2, // Rahmat Hidayat (Creator)
                'amount' => 10000000.00,
                'bank_name' => 'Bank Mandiri',
                'account_number' => '1234567890',
                'account_holder' => 'Rahmat Hidayat',
                'status' => 'paid',
                'purpose' => 'Pembayaran kemoterapi tahap pertama Dek Aisyah di RS Kanker.',
                'notes' => 'Pencairan dana mendesak untuk pembayaran obat kemo.',
                'admin_notes' => 'Bukti medis dan kuitansi valid. Disetujui.',
                'reviewed_at' => now()->subDays(3),
                'paid_at' => now()->subDays(3),
                'transfer_proof' => 'transfer-proofs/sample-proof.png',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id_campaign' => 3, // Beasiswa Sekolah & Buku
                'id_user' => 1, // Yayasan Lentera Bangsa (Creator)
                'amount' => 20000000.00,
                'bank_name' => 'Bank BCA',
                'account_number' => '9876543210',
                'account_holder' => 'Yayasan Lentera Bangsa',
                'status' => 'paid',
                'purpose' => 'Pembelian buku pelajaran dan seragam untuk 80 anak yatim.',
                'notes' => 'Persiapan tahun ajaran baru sekolah.',
                'admin_notes' => 'Rencana Anggaran Biaya (RAB) lengkap. Transfer disetujui.',
                'reviewed_at' => now()->subDays(5),
                'paid_at' => now()->subDays(5),
                'transfer_proof' => 'transfer-proofs/sample-proof.png',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(5),
            ],
        ];

        foreach ($mainWithdrawals as $with) {
            Withdrawal::create($with);
        }

        // 2. Generate Penarikan Dummy Tambahan untuk Kampanye Lain yang memiliki dana terkumpul
        $campaigns = Campaign::where('id_campaign', '>', 3)->get();

        $banks = ['Bank Mandiri', 'Bank BCA', 'Bank BNI', 'Bank BRI', 'Bank CIMB Niaga'];

        foreach ($campaigns as $camp) {
            if ($camp->collected_amount > 2000000) {
                // Tentukan jumlah penarikan (misal: 10% hingga 30% dari dana terkumpul)
                $percent = $faker->randomElement([0.1, 0.2, 0.25]);
                $amount = round(($camp->collected_amount * $percent) / 50000) * 50000; // Bulatkan ke kelipatan 50rb

                if ($amount > 0) {
                    $status = $faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'paid']);
                    
                    $paidAt = null;
                    $reviewedAt = null;
                    $transferProof = null;
                    $adminNotes = null;

                    if ($status === 'paid') {
                        $reviewedAt = now()->subDays(rand(1, 4));
                        $paidAt = $reviewedAt;
                        $transferProof = 'transfer-proofs/sample-proof.png';
                        $adminNotes = 'Pencairan disetujui setelah peninjauan berkas pendukung.';
                    } elseif ($status === 'rejected') {
                        $reviewedAt = now()->subDays(rand(1, 4));
                        $adminNotes = 'Pengajuan ditolak karena Rencana Anggaran Biaya (RAB) kurang detail.';
                    } elseif ($status === 'approved') {
                        $reviewedAt = now()->subDays(rand(1, 2));
                        $adminNotes = 'Pengajuan disetujui, sedang antre di sistem pencairan dana.';
                    }

                    Withdrawal::create([
                        'id_campaign' => $camp->id_campaign,
                        'id_user' => $camp->id_user,
                        'amount' => $amount,
                        'bank_name' => $faker->randomElement($banks),
                        'account_number' => $faker->numerify('##########'),
                        'account_holder' => $camp->user->full_name,
                        'status' => $status,
                        'purpose' => 'Pembelian kebutuhan penyaluran bantuan dan logistik kampanye.',
                        'notes' => 'Pencairan dana untuk operasional penyaluran tahap awal.',
                        'admin_notes' => $adminNotes,
                        'reviewed_at' => $reviewedAt,
                        'paid_at' => $paidAt,
                        'transfer_proof' => $transferProof,
                        'created_at' => now()->subDays(rand(5, 10)),
                        'updated_at' => now(),
                    ]);

                    // Jika status penarikan adalah 'paid', update data di kampanye
                    if ($status === 'paid') {
                        $newWithdrawalAmount = $camp->withdrawal_amount + $amount;
                        $newAvailableBalance = $camp->collected_amount - $newWithdrawalAmount;

                        $camp->update([
                            'withdrawal_amount' => $newWithdrawalAmount,
                            'available_balance' => $newAvailableBalance < 0 ? 0 : $newAvailableBalance,
                        ]);
                    }
                }
            }
        }
    }
}
