<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $name = $firstName . ' ' . $lastName;
            $username = strtolower($firstName . '.' . $faker->numberBetween(10, 99));

            // Random gender
            $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);

            // Random jabatan
            $jabatan = $faker->randomElement([
                'Staf Administrasi',
                'Analis Kebijakan',
                'Pranata Komputer',
                'Pengelola Kepegawaian',
                'Sekretaris',
                'Bendahara'
            ]);

            User::create([
                'nip' => $faker->unique()->numerify('########'),
                'name' => $name,
                'username' => $username,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('pegawai123'), // Default password
                'jabatan' => $jabatan,
                'unit_kerja' => 'Bagian Umum dan Kepegawaian',
                'status_pegawai' => 'ASN',
                'tanggal_masuk' => $faker->date('Y-m-d', '2023-01-01'),
                'jenis_kelamin' => $gender,
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2000-01-01'),
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'role' => 'pegawai',
                'foto' => null,
            ]);
        }
    }
}
