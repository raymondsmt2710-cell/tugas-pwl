<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
