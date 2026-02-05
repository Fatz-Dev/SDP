<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        User::truncate();

        // ===========================================
        // CREATE ADMIN USER (Simplified)
        // ===========================================
        User::create([
            'nip' => '00000000',
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sidapeg.com',
            'password' => 'password123',  
            'jabatan' => 'Administrator',
            'unit_kerja' => 'IT Center',
            'status_pegawai' => 'ASN',
            'tanggal_masuk' => now(),
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'no_hp' => '081234567890',
            'role' => 'admin',
            'foto' => 'default.png',
        ]);


        // ===========================================
        // CALL OTHER SEEDERS
        // ===========================================
        $this->call([
            UserPegawaiSeeder::class,
        ]);

        // ===========================================
        // SUMMARY OUTPUT
        // ===========================================
        $this->command->info('===========================================');
        $this->command->info('DATABASE SEEDER BERHASIL DIEKSEKUSI!');
        $this->command->info('===========================================');
        $this->command->info('Total Users: ' . User::count());
        $this->command->info('===========================================');
        $this->command->info('TEST CREDENTIALS:');
        $this->command->info('Admin: admin / password123');
        $this->command->info('Pegawai: (Check database for generated usernames) / pegawai123');
        $this->command->info('===========================================');
    }
}
