<!DOCTYPE html>
<html>

<head>
    <title>Biodata Pegawai - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 30px;
        }

        .header {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            text-transform: uppercase;
            margin: 0;
            font-size: 16pt;
            color: #1a237e;
        }

        .header p {
            margin: 2px 0;
            font-size: 10pt;
            color: #555;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .title h2 {
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 14pt;
            margin: 0;
        }

        .content-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .content-table td {
            padding: 8px 5px;
            vertical-align: top;
            font-size: 11pt;
        }

        .label {
            width: 30%;
            font-weight: bold;
        }

        .separator {
            width: 3%;
            text-align: center;
        }

        .value {
            width: 67%;
        }

        .photo-section {
            float: right;
            width: 120px;
            height: 150px;
            border: 1px solid #000;
            text-align: center;
            margin-left: 20px;
        }

        .photo-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            line-height: 150px;
            font-size: 10pt;
            color: #999;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .signature-space {
            margin-top: 70px;
        }

        .clear {
            clear: both;
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
        <h2>BIODATA PEGAWAI</h2>
    </div>

    <div class="photo-section">
        @if ($user->foto && file_exists(public_path('assets/img/users/' . $user->foto)))
            <img src="{{ public_path('assets/img/users/' . $user->foto) }}">
        @else
            <div class="photo-placeholder">Pas Foto 3x4</div>
        @endif
    </div>

    <table class="content-table">
        <tr>
            <td class="label">NIP</td>
            <td class="separator">:</td>
            <td class="value"><strong>{{ $user->nip }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->name }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->jenis_kelamin }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->tempat_lahir }},
                {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->unit_kerja }}</td>
        </tr>
        <tr>
            <td class="label">Status Pegawai</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->status_pegawai }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Masuk</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $user->tanggal_masuk ? \Carbon\Carbon::parse($user->tanggal_masuk)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Email</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->email }}</td>
        </tr>
        <tr>
            <td class="label">Nomor HP</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->no_hp }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Lengkap</td>
            <td class="separator">:</td>
            <td class="value">{{ $user->alamat }}</td>
        </tr>
    </table>

    <div class="clear"></div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y') }}</p>
        <p>Pegawai Bersangkutan,</p>
        <div class="signature-space"></div>
        <p><strong>( {{ $user->name }} )</strong></p>
    </div>
</body>

</html>
