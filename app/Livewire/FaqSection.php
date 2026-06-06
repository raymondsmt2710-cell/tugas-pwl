<?php

namespace App\Livewire; // Sesuaikan menjadi App\Http\Livewire jika di dalam folder App/Http/Livewire

use Livewire\Component;

class FaqSection extends Component
{
    public $openIndex = null;

    public $faqs = [
        [
            'q' => 'Bagaimana cara membuat kampanye penggalangan dana di Autopahala?',
            'a' => 'Untuk memulai, Anda perlu mendaftarkan akun terlebih dahulu. Setelah berhasil masuk ke akun Anda, klik tombol "Mulai Galang Dana" yang terletak di halaman navigasi atau di dalam dashboard Anda. Selanjutnya, lengkapi formulir informasi kampanye seperti judul, target dana, kategori yang sesuai, foto/banner pendukung, serta cerita kronologi lengkap mengenai penerima manfaat. Setelah dikirim, tim verifikasi kami akan meninjau kelayakan kampanye Anda dalam waktu maksimal 1x24 jam sebelum diaktifkan secara publik.'
        ],
        [
            'q' => 'Berapa biaya administrasi yang dikenakan oleh platform?',
            'a' => 'Autopahala berkomitmen untuk menyalurkan kebaikan secara maksimal tanpa potongan tersembunyi. Kami hanya mengenakan biaya operasional sistem sebesar 5% khusus untuk kampanye kategori umum guna menunjang pemeliharaan server dan biaya gerbang pembayaran (payment gateway). Sedangkan khusus untuk kampanye kategori tanggap darurat, medis kemanusiaan kritis, dan bencana alam nasional, kami mengenakan potongan 0% alias sepenuhnya gratis tanpa biaya administrasi apa pun.'
        ],
        [
            'q' => 'Bagaimana proses verifikasi untuk kampanye yang diajukan?',
            'a' => 'Keamanan dan transparansi adalah prioritas utama kami. Setiap kampanye yang diajukan akan melalui proses verifikasi oleh tim kami. Penggalang dana wajib mengunggah dokumen identitas diri (KTP) serta dokumen pendukung atau bukti resmi yang relevan dengan tujuan penggalangan dana tersebut. Hal ini kami lakukan demi menjamin keaslian kampanye dan menghindari segala bentuk tindak penipuan.'
        ],
        [
            'q' => 'Bagaimana cara mencairkan dana yang telah terkumpul?',
            'a' => 'Dana yang masuk dan berstatus berhasil (paid) dapat Anda pantau setiap saat secara transparan di dashboard Anda. Untuk mencairkannya, penggalang dana dapat mengajukan pencairan berkala melalui menu "Tarik Dana" dengan mengunggah Rencana Anggaran Biaya (RAB) serta rincian penggunaan kebutuhan mendesak terbaru. Setelah diajukan, dana akan ditransfer langsung ke rekening bank terdaftar yang telah divalidasi oleh tim keuangan kami dalam waktu 2-3 hari kerja.'
        ],
        [
            'q' => 'Apakah saya bisa memberikan donasi secara anonim?',
            'a' => 'Ya, Anda tentu bisa memberikan donasi secara anonim. Saat Anda mengisi formulir pembayaran donasi di kampanye mana pun, Anda cukup mengosongkan kolom nama donatur atau menuliskan nama samaran (seperti Hamba Allah). Sistem kami tidak akan menampilkan identitas asli Anda secara publik di halaman detail kampanye maupun di papan peringkat donatur teratas (Leaderboard), namun laporan penyaluran dana tetap tercatat sah secara akuntansi di sistem internal.'
        ]
    ];

    public function toggle($index)
    {
        if ($this->openIndex === $index) {
            $this->openIndex = null;
        } else {
            $this->openIndex = $index;
        }
    }

    public function render()
    {
        return view('livewire.faq-section');
    }
}