<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SiswaTemplateExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Return an empty collection or an example row
        return new Collection([
            [
                'Budi Santoso',
                'budisantoso',
                'budi@example.com',
                'password123',
                '12345678',
                'L',
                '08123456789',
                'Jl. Raya No. 123, Jakarta'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'username',
            'email',
            'password',
            'nis',
            'jenis_kelamin',
            'no_telepon',
            'alamat',
        ];
    }
}
