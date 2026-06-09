<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('id_ID');

        // 1. User Utama (Sesuai DatabaseSeeder lama)
        $mainUsers = [
            [
                'id_user' => 1,
                'full_name' => 'Yayasan Lentera Bangsa',
                'username' => 'lentera_bangsa',
                'email' => 'lentera.bangsa@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '1234567890123456',
                'phone_number' => '081234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'bio' => 'Yayasan kemanusiaan yang berfokus pada kesejahteraan dan bantuan sosial masyarakat kurang mampu.',
                'profile_photo' => null,
                'avatar_url' => null,
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
                'nik' => '2134567890123456',
                'phone_number' => '081234567891',
                'address' => 'Jl. Mawar No. 4, Bandung',
                'bio' => 'Individu yang peduli sesama dan ingin menjadi jembatan kebaikan bagi orang-orang di sekitar.',
                'profile_photo' => null,
                'avatar_url' => null,
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
                'nik' => '3124567890123456',
                'phone_number' => '081234567892',
                'address' => 'Desa Sukamaju RT 01/02, Bogor',
                'bio' => 'Kumpulan pemuda kreatif dan aktif dalam kegiatan pembangunan desa serta tanggap bencana alam daerah.',
                'profile_photo' => null,
                'avatar_url' => null,
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
                'nik' => '4123567890123456',
                'phone_number' => '081234567893',
                'address' => 'Jl. Melati No. 12, Surabaya',
                'bio' => 'Senang berbagi dan berpartisipasi aktif dalam berbagai macam aksi sosial untuk kemajuan bersama.',
                'profile_photo' => null,
                'avatar_url' => null,
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
                'nik' => '5123467890123456',
                'phone_number' => '081234567894',
                'address' => 'Apartemen Sudirman Park Tower B, Jakarta',
                'bio' => 'Seorang profesional muda yang aktif mendukung gerakan penyelamatan lingkungan dan pelindung hewan liar.',
                'profile_photo' => null,
                'avatar_url' => null,
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
                'nik' => '6123457890123456',
                'phone_number' => '081234567895',
                'address' => 'Kantor Pusat Autopahala, Medan',
                'bio' => 'Akun Administrator Utama platform Autopahala untuk pengelolaan sistem dan verifikasi kampanye.',
                'profile_photo' => null,
                'avatar_url' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($mainUsers as $user) {
            User::create($user);
        }

        // 2. Dummy Users menggunakan Faker
        for ($i = 7; $i <= 25; $i++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $fullName = $firstName . ' ' . $lastName;
            $username = strtolower($firstName . '_' . $lastName . '_' . rand(10, 99));
            $email = $username . '@gmail.com';

            // Tentukan apakah user ini memiliki profil lengkap (siap membuat kampanye)
            $isCompleteProfile = $faker->boolean(70); // 70% peluang profil lengkap

            User::create([
                'id_user' => $i,
                'full_name' => $fullName,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => $isCompleteProfile ? $faker->numerify('################') : null,
                'phone_number' => $isCompleteProfile ? $faker->phoneNumber() : null,
                'address' => $isCompleteProfile ? $faker->address() : null,
                'bio' => $isCompleteProfile ? $faker->realTextBetween(55, 150) : null,
                'profile_photo' => null,
                'avatar_url' => null,
                'email_verified_at' => now(),
                'created_at' => now()->subDays(rand(10, 60)),
                'updated_at' => now(),
            ]);
        }
    }
}
