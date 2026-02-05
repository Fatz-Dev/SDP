<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Hitung jarak antara dua koordinat menggunakan formula Haversine
     * @return float Jarak dalam meter
     */
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Ambil pengaturan geolocation dari database
     */
    private function getGeolocationSettings()
    {
        return [
            'latitude' => (float) Setting::where('key', 'office_latitude')->value('value') ?? -6.2088,
            'longitude' => (float) Setting::where('key', 'office_longitude')->value('value') ?? 106.8456,
            'radius' => (int) Setting::where('key', 'office_radius')->value('value') ?? 100,
            'enabled' => (bool) Setting::where('key', 'geofencing_enabled')->value('value') ?? true,
            'office_name' => Setting::where('key', 'office_name')->value('value') ?? 'Kantor',
        ];
    }

    /**
     * Cek apakah lokasi pegawai dalam radius kantor (API endpoint)
     */
    public function checkLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $settings = $this->getGeolocationSettings();

        // Jika geofencing dinonaktifkan, selalu izinkan
        if (!$settings['enabled']) {
            return response()->json([
                'inRange' => true,
                'geofencingEnabled' => false,
                'message' => 'Geofencing tidak aktif',
            ]);
        }

        $userLat = $request->latitude;
        $userLon = $request->longitude;

        $distance = $this->haversine(
            $userLat,
            $userLon,
            $settings['latitude'],
            $settings['longitude']
        );

        $inRange = $distance <= $settings['radius'];

        return response()->json([
            'inRange' => $inRange,
            'distance' => round($distance),
            'maxRadius' => $settings['radius'],
            'officeName' => $settings['office_name'],
            'geofencingEnabled' => true,
            'message' => $inRange
                ? 'Anda berada dalam jangkauan kantor'
                : 'Anda berada di luar jangkauan kantor (' . round($distance) . 'm dari ' . $settings['office_name'] . ')',
        ]);
    }

    public function index()
    {
        $userId = session('user_id');
        $today = Carbon::today();

        $absensiHariIni = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayatAbsensi = Absensi::where('user_id', $userId)
            ->latest('tanggal')
            ->paginate(10);

        // Kirim pengaturan geolocation ke view
        $geoSettings = $this->getGeolocationSettings();

        return view('pages.pegawai.absensi.index', compact('absensiHariIni', 'riwayatAbsensi', 'geoSettings'));
    }

    public function masuk(Request $request)
    {
        $userId = session('user_id');
        $today = Carbon::today();

        // Cek apakah sudah absen hari ini
        $exists = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // Validasi lokasi jika geofencing aktif
        $settings = $this->getGeolocationSettings();
        if ($settings['enabled'] && $request->lokasi) {
            $coords = explode(',', $request->lokasi);
            if (count($coords) === 2) {
                $distance = $this->haversine(
                    (float) $coords[0],
                    (float) $coords[1],
                    $settings['latitude'],
                    $settings['longitude']
                );

                if ($distance > $settings['radius']) {
                    return back()->with('error', 'Anda berada di luar jangkauan kantor (' . round($distance) . 'm). Absen ditolak.');
                }
            }
        }

        $request->validate([
            'lokasi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = 'masuk_' . $userId . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/img/absensi'), $fotoName);
        }

        Absensi::create([
            'user_id' => $userId,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->toTimeString(),
            'status' => 'Hadir',
            'lokasi_masuk' => $request->lokasi,
            'foto_masuk' => $fotoName,
        ]);

        return back()->with('success', 'Berhasil melakukan absen masuk!');
    }

    public function pulang(Request $request)
    {
        $userId = session('user_id');
        $today = Carbon::today();

        $absensi = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        if ($absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        // Validasi lokasi jika geofencing aktif
        $settings = $this->getGeolocationSettings();
        if ($settings['enabled'] && $request->lokasi) {
            $coords = explode(',', $request->lokasi);
            if (count($coords) === 2) {
                $distance = $this->haversine(
                    (float) $coords[0],
                    (float) $coords[1],
                    $settings['latitude'],
                    $settings['longitude']
                );

                if ($distance > $settings['radius']) {
                    return back()->with('error', 'Anda berada di luar jangkauan kantor (' . round($distance) . 'm). Absen ditolak.');
                }
            }
        }

        $request->validate([
            'lokasi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = 'pulang_' . $userId . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/img/absensi'), $fotoName);
        }

        $absensi->update([
            'jam_pulang' => Carbon::now()->toTimeString(),
            'lokasi_pulang' => $request->lokasi,
            'foto_pulang' => $fotoName,
        ]);

        return back()->with('success', 'Berhasil melakukan absen pulang!');
    }
}
