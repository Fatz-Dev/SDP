<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'office_name' => 'PEMERINTAH KOTA ADMINISTRASI JAKARTA PUSAT',
            'office_address' => 'Jl. Tanah Abang I No.1, RT.11/RW.8, Petojo Sel., Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10160',
            'office_phone' => '(021) 3502575',
            'office_email' => 'info@jakartapusat.go.id',
            'office_website' => 'www.jakartapusat.go.id',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
