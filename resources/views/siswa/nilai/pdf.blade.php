<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Nilai - {{ $user->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Public Sans', -apple-system, sans-serif;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 2px 0;
        }

        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .nilai-table th,
        .nilai-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .nilai-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .signature {
            margin-top: 60px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
        }

        .bg-success {
            background-color: #71dd37;
        }

        .bg-danger {
            background-color: #ff3e1d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Capaian Hasil Belajar</h2>
        <p>E-Learning System - {{ config('app.name') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="150">Nama Siswa</td>
            <td width="20">:</td>
            <td><strong>{{ $user->nama_lengkap }}</strong></td>
            <td width="100">NIS</td>
            <td width="20">:</td>
            <td>{{ $user->nis }}</td>
        </tr>
        <tr>
            <td>Username</td>
            <td>:</td>
            <td>{{ $user->username }}</td>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{ $tanggal }}</td>
        </tr>
    </table>

    <table class="nilai-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>KKM</th>
                <th>Nilai Akhir</th>
                <th>Huruf</th>
                <th>Predikat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left;">{{ $item->mataPelajaran->nama_mapel }}</td>
                    <td>{{ number_format($item->kkm, 0) }}</td>
                    <td><strong>{{ number_format($item->nilai_akhir, 2) }}</strong></td>
                    <td>{{ $item->nilai_huruf }}</td>
                    <td>{{ $item->predikat }}</td>
                    <td>
                        @if ($item->lulus)
                            LULUS
                        @else
                            TIDAK LULUS
                        @else
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dikeluarkan pada: {{ $tanggal }}</p>
        <div class="signature">
            <p>Mengetahui,</p>
            <p style="margin-top: 80px;">__________________________</p>
            <p>Kepala Sekolah / Wali Kelas</p>
        </div>
    </div>
</body>

</html>
