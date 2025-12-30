<!DOCTYPE html>
<html>

<head>
    <title>Rekap Nilai Siswa - {{ $kelas->nama_kelas }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 15px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a237e;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .school-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }

        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 15px;
        }

        .meta-info td {
            padding: 2px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: center;
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            font-size: 10px;
        }

        .data-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .text-left {
            text-align: left !important;
        }

        .footer {
            margin-top: 40px;
            width: 100%;
        }

        .signature {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }

        .print-date {
            font-size: 9px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="school-name">{{ $settings->nama_sekolah }}</div>
        <div class="school-info">{{ $settings->alamat_sekolah }}</div>
        <div class="school-info">Email: {{ $settings->email_kontak }} | Telp: {{ $settings->no_telepon }}</div>
    </div>

    <div class="report-title">REKAPITULASI AKTIVITAS NILAI SISWA</div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Kelas</strong></td>
            <td width="2%">:</td>
            <td width="33%">{{ $kelas->nama_kelas }}</td>
            <td width="15%"><strong>Tahun Ajaran</strong></td>
            <td width="2%">:</td>
            <td width="33%">{{ $akademik->tahun_ajaran ?? '-' }} ({{ ucfirst($akademik->semester ?? '-') }})</td>
        </tr>
        <tr>
            <td><strong>Tanggal Cetak</strong></td>
            <td>:</td>
            <td colspan="4">{{ $tanggal }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th class="text-left">Nama Siswa</th>
                <th width="15%">NIS</th>
                <th width="12%">Jlh Tugas</th>
                <th width="12%">Jlh Kuis</th>
                <th width="12%">Jlh Ujian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $index => $s)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $s->nama_lengkap }}</strong></td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->pengumpulanTugas->count() }}</td>
                    <td>{{ $s->jawabanKuis->count() }}</td>
                    <td>{{ $s->jawabanUjian->count() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="print-date">Dicetak melalui sistem pada: {{ now()->format('d/m/Y H:i') }}</div>
        <div class="signature">
            <div>Administrator,</div>
            <div class="signature-space"></div>
            <div style="font-weight: bold; text-decoration: underline;">{{ auth()->user()->nama_lengkap }}</div>
            <div>NIP. {{ auth()->user()->nip ?: '-' }}</div>
        </div>
    </div>
</body>

</html>
