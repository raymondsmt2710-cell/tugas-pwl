<?php

namespace App\Livewire;

use Livewire\Component;

class FaqSection extends Component
{
    public ?int $openIndex = null;

    public array $faqs = [
        ['q' => 'Bagaimana cara membuat kampanye?', 'a' => 'Daftar akun, lalu klik "Buat Kampanye" di halaman profil Anda. Isi detail kampanye, upload gambar, dan ajukan untuk review. Setelah disetujui admin, kampanye Anda akan tampil ke publik.'],
        ['q' => 'Berapa lama proses verifikasi kampanye?', 'a' => 'Proses verifikasi biasanya memakan waktu 1-3 hari kerja. Tim kami akan meninjau kelengkapan informasi dan keaslian kampanye Anda.'],
        ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Kami mendukung transfer bank (BCA, BNI, Mandiri, BRI), e-wallet (GoPay, OVO, Dana), kartu kredit/debit, dan virtual account melalui Midtrans.'],
        ['q' => 'Bagaimana cara menarik dana?', 'a' => 'Masuk ke halaman kampanye Anda, klik "Tarik Dana", masukkan jumlah dan detail rekening bank. Penarikan akan diproses setelah disetujui admin dalam 1-3 hari kerja.'],
        ['q' => 'Apakah ada biaya platform?', 'a' => 'Autopahala tidak memungut biaya platform. Biaya yang dikenakan hanya biaya payment gateway dari Midtrans sesuai metode pembayaran yang dipilih donatur.'],
        ['q' => 'Apakah donasi saya aman?', 'a' => 'Ya. Semua transaksi diproses melalui Midtrans yang tersertifikasi PCI-DSS. Kampanye juga diverifikasi oleh tim kami sebelum ditampilkan ke publik.'],
    ];

    public function toggle(int $index): void
    {
        $this->openIndex = $this->openIndex === $index ? null : $index;
    }

    public function render()
    {
        return view('livewire.faq-section');
    }
}
