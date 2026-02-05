<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default geolocation settings
        $settings = [
            ['key' => 'office_latitude', 'value' => '-6.2088', 'type' => 'text'],
            ['key' => 'office_longitude', 'value' => '106.8456', 'type' => 'text'],
            ['key' => 'office_radius', 'value' => '100', 'type' => 'number'],
            ['key' => 'geofencing_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'office_name', 'value' => 'Kantor Pusat', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'office_latitude',
            'office_longitude',
            'office_radius',
            'geofencing_enabled',
            'office_name',
        ])->delete();
    }
};
