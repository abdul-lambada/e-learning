<!DOCTYPE html>
<html>

<head>
    <title>Laporan Nilai Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .school-info {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .school-detail {
            font-size: 10px;
            font-weight: normal;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 3px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .data-table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 50px;
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-space {
            height: 70px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="school-info">{{ $appSettings->nama_sekolah }}</div>
        <div class="school-detail">{{ $appSettings->alamat_sekolah }}</div>
        <div class="school-detail">Email: {{ $appSettings->email_kontak }} | Telp: {{ $appSettings->no_telepon }}</div>
    </div>

    <div class="title" style="text-align: center;">LAPORAN HASIL BELAJAR (DAFTAR NILAI)</div>

    <table class="info-table">
        <tr>
            <td width="15%">Kelas</td>
            <td width="2%">:</td>
            <td width="33%">{{ $guruMengajar->kelas->nama_kelas }}</td>
            <td width="15%">Mata Pelajaran</td>
            <td width="2%">:</td>
            <td width="33%">{{ $guruMengajar->mapel->nama_mapel }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $activeAkademik->tahun_ajaran ?? '-' }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ ucfirst($activeAkademik->semester ?? '-') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Siswa</th>
                <th>NIS</th>
                <th>Tugas ({{ $bobot['tugas'] }}%)</th>
                <th>Kuis ({{ $bobot['kuis'] }}%)</th>
                <th>Ujian ({{ $bobot['ujian'] }}%)</th>
                <th>Absensi ({{ $bobot['absensi'] }}%)</th>
                <th style="font-weight: bold;">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_nilai as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left;">{{ $item['nama'] }}</td>
                    <td>{{ $item['nis'] }}</td>
                    <td>{{ number_format($item['tugas'], 2) }}</td>
                    <td>{{ number_format($item['kuis'], 2) }}</td>
                    <td>{{ number_format($item['ujian'], 2) }}</td>
                    <td>{{ number_format($item['absensi'], 2) }}</td>
                    <td style="font-weight: bold;">{{ number_format($item['akhir'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>Dicetak pada: {{ date('d/m/Y') }}</div>
        <div style="margin-top: 10px;">Guru Mata Pelajaran,</div>
        <div class="signature-space"></div>
        <div style="font-weight: bold; text-decoration: underline;">{{ auth()->user()->nama_lengkap }}</div>
        <div>NIP. {{ auth()->user()->nip ?: '-' }}</div>
    </div>
</body>

</html>
