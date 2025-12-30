<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SoalKuisTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return collect([
            [
                '1',
                'pilihan_ganda',
                'Berapa hasil dari 2 + 2?',
                '4',
                '3',
                '2',
                '5',
                '',
                'A',
                '10',
                'Penjumlahan sederhana.'
            ],
            [
                '2',
                'essay',
                'Jelaskan apa yang dimaksud dengan fotosintesis!',
                '',
                '',
                '',
                '',
                '',
                '',
                '20',
                'Proses tumbuhan membuat makanan.'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Nomor Soal',
            'Tipe Soal',
            'Pertanyaan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Pilihan E',
            'Kunci Jawaban',
            'Bobot Nilai',
            'Pembahasan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
