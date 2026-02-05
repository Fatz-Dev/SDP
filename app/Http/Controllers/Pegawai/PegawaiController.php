<?php
// app/Http/Controllers/PegawaiController.php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pegawai');

        // Search filter
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->status) {
            $query->where('status_pegawai', $status);
        }

        // Unit filter
        if ($unit = $request->unit) {
            $query->where('unit_kerja', $unit);
        }

        $pegawai = $query->latest()->paginate(10);
        $units = User::where('role', 'pegawai')->distinct('unit_kerja')->pluck('unit_kerja');

        return view('pages.admin.pegawai.index', compact('pegawai', 'units'));
    }

    public function search(Request $request)
    {
        $query = User::where('role', 'pegawai');

        // Search filter
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->status) {
            $query->where('status_pegawai', $status);
        }

        // Unit filter
        if ($unit = $request->unit) {
            $query->where('unit_kerja', $unit);
        }

        $pegawai = $query->latest()->paginate(10);

        // Return partial view for AJAX
        return view('pages.admin.pegawai.partials.table', compact('pegawai'));
    }

    public function create()
    {
        return view('pages.admin.pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:users|min:8',
            'name' => 'required|string|max:100',
            'username' => 'required|string|unique:users',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'status_pegawai' => 'required|in:ASN,Non ASN,Honorer',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:8',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('assets/img/users'), $filename);
            $validated['foto'] = $filename;
        }

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'pegawai';

        User::create($validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    public function show(User $pegawai)
    {
        return view('pages.admin.pegawai.show', compact('pegawai'));
    }

    public function edit(User $pegawai)
    {
        return view('pages.admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, User $pegawai)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:users,nip,' . $pegawai->id . '|min:8',
            'name' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'status_pegawai' => 'required|in:ASN,Non ASN,Honorer',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika bukan default
            if ($pegawai->foto != 'default.png') {
                $oldFoto = public_path('assets/img/users/' . $pegawai->foto);
                if (file_exists($oldFoto)) {
                    unlink($oldFoto);
                }
            }

            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('assets/img/users'), $filename);
            $validated['foto'] = $filename;
        }

        $pegawai->update($validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(User $pegawai)
    {
        // Hapus foto jika bukan default
        if ($pegawai->foto != 'default.png') {
            $fotoPath = public_path('assets/img/users/' . $pegawai->foto);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus!');
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pegawai');

        // Header Table
        $headers = [
            'A1' => 'NIP',
            'B1' => 'Nama Lengkap',
            'C1' => 'Jenis Kelamin',
            'D1' => 'Tempat Lahir',
            'E1' => 'Tanggal Lahir',
            'F1' => 'Jabatan',
            'G1' => 'Unit Kerja',
            'H1' => 'Status Pegawai',
            'I1' => 'Tanggal Masuk',
            'J1' => 'Email',
            'K1' => 'No HP',
            'L1' => 'Alamat'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style Header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E88E5'], // Blue UI color
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Data Pegawai
        $pegawai = User::where('role', 'pegawai')->get();
        $row = 2;

        foreach ($pegawai as $p) {
            // Force NIP and HP as String to prevent Scientific Notation (1.9E+11)
            $sheet->setCellValueExplicit('A' . $row, $p->nip, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $row, $p->name);
            $sheet->setCellValue('C' . $row, $p->jenis_kelamin);
            $sheet->setCellValue('D' . $row, $p->tempat_lahir);
            $sheet->setCellValue('E' . $row, $p->tanggal_lahir);
            $sheet->setCellValue('F' . $row, $p->jabatan);
            $sheet->setCellValue('G' . $row, $p->unit_kerja);
            $sheet->setCellValue('H' . $row, $p->status_pegawai);
            $sheet->setCellValue('I' . $row, $p->tanggal_masuk);
            $sheet->setCellValue('J' . $row, $p->email);
            // Force HP as String
            $sheet->setCellValueExplicit('K' . $row, $p->no_hp, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('L' . $row, $p->alamat);

            // Alignment for data
            $sheet->getStyle('A' . $row . ':L' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $row++;
        }

        // Apply Borders to all data
        $contentRange = 'A1:L' . ($row - 1);
        $sheet->getStyle($contentRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Auto Size Columns
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Export as Xlsx
        $fileName = 'Data_Pegawai_SIDAPEG_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
