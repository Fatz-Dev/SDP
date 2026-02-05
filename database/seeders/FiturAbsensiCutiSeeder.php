<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use App\Models\PengajuanCuti;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FiturAbsensiCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User Admin jika belum ada
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nip' => '12345678',
                'name' => 'Administrator',
                'email' => 'admin@sidapeg.com',
                'password' => Hash::make('password'),
                'jabatan' => 'Kepala HRD',
                'unit_kerja' => 'Umum',
                'status_pegawai' => 'ASN',
                'tanggal_masuk' => '2020-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-05-20',
                'no_hp' => '081234567890',
                'role' => 'admin'
            ]
        );

        // 2. Buat User Pegawai
        $pegawai = User::updateOrCreate(
            ['username' => 'budisantoso'],
            [
                'nip' => '87654321',
                'name' => 'Budi Santoso',
                'email' => 'budi@sidapeg.com',
                'password' => Hash::make('password'),
                'jabatan' => 'Staff IT',
                'unit_kerja' => 'Teknologi Informasi',
                'status_pegawai' => 'Non ASN',
                'tanggal_masuk' => '2023-06-15',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1995-12-10',
                'no_hp' => '089876543210',
                'role' => 'pegawai'
            ]
        );

        // 3. Tambahkan Absensi Contoh
        Absensi::create([
            'user_id' => $pegawai->id,
            'tanggal' => Carbon::yesterday(),
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '17:00:00',
            'status' => 'Hadir',
            'lokasi_masuk' => '-6.1751,106.8272',
            'lokasi_pulang' => '-6.1751,106.8272',
        ]);

        // 4. Tambahkan Pengajuan Cuti Contoh
        PengajuanCuti::create([
            'user_id' => $pegawai->id,
            'jenis_cuti' => 'Cuti Tahunan',
            'tanggal_mulai' => Carbon::tomorrow()->toDateString(),
            'tanggal_selesai' => Carbon::tomorrow()->addDays(2)->toDateString(),
            'alasan' => 'Ada keperluan keluarga di luar kota.',
            'status' => 'Pending'
        ]);
    }
}
