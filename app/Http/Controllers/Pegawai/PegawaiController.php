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
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // ===========================================
        // MAIN TITLE (Row 1 & 2)
        // ===========================================
        $sheet->setCellValue('A1', 'REKAPITULASI DATA PEGAWAI');
        $sheet->setCellValue('A2', 'SISTEM INFORMASI DATA PEGAWAI (SIDAPEG)');

        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');

        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => '000000'], // Hitam
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:L2')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // ===========================================
        // TABLE HEADERS (Row 4)
        // ===========================================
        $headers = [
            'A4' => 'NO',
            'B4' => 'NIP',
            'C4' => 'NAMA LENGKAP',
            'D4' => 'JABATAN',
            'E4' => 'UNIT KERJA',
            'F4' => 'STATUS',
            'G4' => 'JENIS KELAMIN',
            'H4' => 'TEMPAT LAHIR',
            'I4' => 'TANGGAL LAHIR',
            'J4' => 'TANGGAL MASUK',
            'K4' => 'NO HP',
            'L4' => 'ALAMAT'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style Header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A4:L4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(25);

        // ===========================================
        // DATA PEGAWAI (Starts from Row 5)
        // ===========================================
        $pegawai = User::where('role', 'pegawai')->get();
        $row = 5;
        $no = 1;

        foreach ($pegawai as $p) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValueExplicit('B' . $row, $p->nip, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $p->name);
            $sheet->setCellValue('D' . $row, $p->jabatan);
            $sheet->setCellValue('E' . $row, $p->unit_kerja);
            $sheet->setCellValue('F' . $row, $p->status_pegawai);
            $sheet->setCellValue('G' . $row, $p->jenis_kelamin);
            $sheet->setCellValue('H' . $row, $p->tempat_lahir);
            $sheet->setCellValue('I' . $row, $p->tanggal_lahir ? date('d-m-Y', strtotime($p->tanggal_lahir)) : '-');
            $sheet->setCellValue('J' . $row, $p->tanggal_masuk ? date('d-m-Y', strtotime($p->tanggal_masuk)) : '-');
            $sheet->setCellValueExplicit('K' . $row, $p->no_hp, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('L' . $row, $p->alamat);

            // Alignment and Borders for each row
            $sheet->getStyle('A' . $row . ':L' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A' . $row . ':L' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alternating Row Colors
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':L' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF5F5F5');
            }

            $row++;
        }

        // Auto Size Columns
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Export as Xlsx
        $fileName = 'Rekap_Pegawai_SIDAPEG_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $pegawai = User::where('role', 'pegawai')->get();

        $settings = [
            'office_name' => Setting::where('key', 'office_name')->value('value') ?? 'SIDAPEG',
            'office_address' => Setting::where('key', 'office_address')->value('value') ?? 'Alamat Belum Diatur',
            'office_phone' => Setting::where('key', 'office_phone')->value('value') ?? '-',
            'office_email' => Setting::where('key', 'office_email')->value('value') ?? '-',
        ];

        $pdf = Pdf::loadView('pages.admin.pegawai.export-pdf', compact('pegawai', 'settings'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Data_Pegawai_SIDAPEG_' . date('Ymd_His') . '.pdf');
    }
}
