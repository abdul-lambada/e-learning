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
