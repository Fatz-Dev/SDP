<!DOCTYPE html>
<html>

<head>
    <title>Data Pegawai SIDAPEG</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .header h1 {
            text-transform: uppercase;
            margin: 0;
            font-size: 18pt;
            color: #000000;
        }

        .header p {
            margin: 2px 0;
            font-size: 10pt;
            color: #555;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h2 {
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 14pt;
            margin: 0;
        }

        .title p {
            font-size: 10pt;
            margin: 5px 0 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        table th {
            background-color: #f2f2f2;
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11pt;
        }

        .footer .signature-space {
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $settings['office_name'] }}</h1>
        <p>{{ $settings['office_address'] }}</p>
        <p>Telepon: {{ $settings['office_phone'] }} | Email: {{ $settings['office_email'] }}</p>
    </div>

    <div class="title">
        <h2>REKAPITULASI DATA PEGAWAI</h2>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIP</th>
                <th width="25%">Nama Lengkap</th>
                <th width="20%">Jabatan</th>
                <th width="20%">Unit Kerja</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawai as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $p->nip }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td>{{ $p->unit_kerja }}</td>
                    <td class="text-center">{{ $p->status_pegawai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p><strong>Kepala Bagian Umum</strong></p>
        <div class="signature-space"></div>
        <p>__________________________</p>
    </div>
</body>

</html>
