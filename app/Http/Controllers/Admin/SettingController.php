<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil semua pengaturan geolocation
        $settings = [
            'office_latitude' => Setting::where('key', 'office_latitude')->value('value') ?? '-6.2088',
            'office_longitude' => Setting::where('key', 'office_longitude')->value('value') ?? '106.8456',
            'office_radius' => Setting::where('key', 'office_radius')->value('value') ?? '100',
            'geofencing_enabled' => (bool) (Setting::where('key', 'geofencing_enabled')->value('value') ?? true),
            'office_name' => Setting::where('key', 'office_name')->value('value') ?? 'Kantor Pusat',
            'work_start_time' => Setting::where('key', 'work_start_time')->value('value') ?? '08:00',
        ];

        return view('pages.admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->input('settings', []);

        if (is_array($data)) {
            // Handle checkbox untuk geofencing_enabled
            if (!isset($data['geofencing_enabled'])) {
                $data['geofencing_enabled'] = '0';
            }

            foreach ($data as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->back()->with('success', 'Pengaturan lokasi kantor berhasil diperbarui!');
    }
}
