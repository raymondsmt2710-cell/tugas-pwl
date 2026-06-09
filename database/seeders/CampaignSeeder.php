<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        // Determine users with complete profiles for campaign ownership
        $eligibleUsers = User::whereNotNull('nik')->pluck('id_user')->toArray();
        if (empty($eligibleUsers)) {
            $eligibleUsers = [1, 2, 3, 4, 5];
        }

        // Generate 30 dummy campaigns with predefined titles and descriptions (no Faker for title/description)
        $predefinedCampaigns = [
            [
                'title' => 'Patungan Kursi Roda dan Kaki Palsu untuk Kaum Dhuafa',
                'desc' => 'Banyak saudara kita yang difabel dari kalangan tidak mampu kesulitan beraktivitas karena tidak memiliki alat bantu yang layak. Program ini bertujuan menggalang dana untuk membeli 30 kursi roda dan kaki palsu yang akan dibagikan secara gratis.',
            ],
            [
                'title' => 'Renovasi Sekolah Dasar Terpencil di Pelosok Nias',
                'desc' => 'Kondisi atap sekolah SD Swasta Nias bocor dan dinding kayunya sudah lapuk termakan usia. Ketika hujan turun, aktivitas belajar mengajar terpaksa dihentikan demi keselamatan murid. Mari patungan merenovasi ruang kelas mereka.',
            ],
            [
                'title' => 'Bantuan Pangan & Tenda Darurat Korban Longsor Cianjur',
                'desc' => 'Curah hujan tinggi memicu bencana tanah longsor di beberapa desa di Kabupaten Cianjur. Puluhan keluarga terpaksa mengungsi ke posko darurat dengan perbekalan yang minim. Donasi Anda akan disalurkan dalam bentuk makanan pokok, selimut, susu balita, dan obat-obatan.',
            ],
            [
                'title' => 'Program Sembako & Layanan Kesehatan Panti Jompo Lentera Hati',
                'desc' => 'Panti Jompo Lentera Hati saat ini menampung 45 lansia telantar. Kebutuhan pangan harian serta obat-obatan rutin untuk lansia yang mengidap penyakit bawaan sering kali kekurangan. Uluran tangan Anda sangat berarti untuk kehangatan hari tua mereka.',
            ],
            [
                'title' => 'Gotong Royong Perbaikan Sarana Air Bersih Desa Karanganyar',
                'desc' => 'Musim kemarau panjang membuat sumber air warga Desa Karanganyar mengering. Warga harus berjalan kaki sejauh 3 kilometer ke lembah terdekat demi mendapatkan air bersih. Kami berencana membangun sumur bor sedalam 60 meter dan memasang pipa air ke pemukiman.',
            ],
            [
                'title' => 'Aksi Tanam 1000 Bibit Mangrove untuk Cegah Abrasi Pantai Utara',
                'desc' => 'Garis pantai utara mengalami abrasi parah akibat hilangnya hutan bakau alami. Gelombang air laut mulai mengikis pemukiman warga pesisir. Kami mengajak relawan dan donor untuk ikut mendanai bibit mangrove serta penanaman massal di akhir bulan.',
            ],
            [
                'title' => 'Penyelamatan & Shelter Medis untuk Kucing Liar Telantar',
                'desc' => 'Shelter kami menampung kucing-kucing jalanan yang sakit, terluka, atau dibuang. Biaya pakan harian serta tagihan klinik hewan untuk sterilisasi dan pengobatan terus menumpuk. Mari bantu kami menyediakan tempat bernaung yang layak bagi mereka.',
            ],
            [
                'title' => 'Pengadaan Laptop Bicara & Alat Tulis Braille untuk Tunanetra',
                'desc' => 'Akses pendidikan bagi siswa tunanetra sangat terbatas karena minimnya fasilitas belajar khusus. Melalui donasi ini, kami ingin membagikan 15 unit laptop dengan software pembaca layar (screen reader) serta buku tulis braille untuk menunjang sekolah mereka.',
            ],
            [
                'title' => 'Santunan Kasih & Paket Nutrisi Lengkap Anak Panti Asuhan',
                'desc' => 'Menjamin masa depan anak-anak panti asuhan bukan hanya tentang tempat tinggal, tapi juga gizi seimbang yang cukup. Program ini bertujuan menggalang dana untuk persediaan makanan bergizi, vitamin, dan biaya pendidikan bulanan.',
            ],
            [
                'title' => 'Bantu Dek Fatih Berjuang Melawan Atresia Bilier (Gagal Hati)',
                'desc' => 'Dek Fatih yang berumur 9 bulan didiagnosis gagal hati kronis dan membutuhkan tindakan transplantasi hati segera di rumah sakit rujukan nasional. Biaya operasi yang tidak sepenuhnya ditanggung jaminan kesehatan sangatlah besar bagi orang tuanya yang buruh harian.',
            ],
            [
                'title' => 'Beasiswa Penuh & Paket Seragam Sekolah Anak Jalanan',
                'desc' => 'Aksi sosial ini dirancang untuk mendampingi anak-anak jalanan agar bisa kembali mengenyam pendidikan formal. Dana terkumpul akan digunakan untuk membayai SPP sekolah swasta mitra, seragam, sepatu, dan tas sekolah.',
            ],
            [
                'title' => 'Dapur Pangan Berjalan: Makanan Gratis Mingguan untuk Pekerja Informal',
                'desc' => 'Setiap hari Jumat, tim relawan kami membagikan 200 porsi makanan siap saji bergizi gratis bagi driver ojek online, pemulung, tukang sapu jalanan, dan pekerja informal di jalanan kota. Mari bergabung menyukseskan program kebaikan ini.',
            ],
        ];
        $preCount = count($predefinedCampaigns);
        for ($i = 0; $i < 30; $i++) {
            $userId = $faker->randomElement($eligibleUsers);
            $categoryId = $faker->numberBetween(1, 12);
            $data = $predefinedCampaigns[$i % $preCount];
            $title = $data['title'];
            $slug = Str::slug($title . '-' . $i);
            $shortDesc = $data['desc'];
            $description = $data['desc'];
            $target = $faker->randomElement([15000000, 30000000, 50000000, 75000000, 100000000]);
            $status = $faker->randomElement(['active', 'active', 'finished']);

            Campaign::create([
                'id_user' => $userId,
                'id_category' => $categoryId,
                'title' => $title,
                'slug' => $slug,
                'short_description' => $shortDesc,
                'description' => $description,
                'target_amount' => $target,
                'minimum_donation' => $faker->randomElement([10000, 15000, 20000, 50000]),
                'collected_amount' => 0.00,
                'withdrawal_amount' => 0.00,
                'available_balance' => 0.00,
                'banner_image' => null,
                'video_url' => null,
                'campaign_status' => $status,
                'verification_status' => 'active',
                'status' => 'approved',
                'start_date' => now()->subDays(rand(5, 30)),
                'end_date' => now()->addDays(rand(10, 60)),
                'created_at' => now()->subDays(rand(5, 30)),
                'updated_at' => now(),
            ]);
        }


    }
}
