<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $role;

    public function __construct($role = 'siswa')
    {
        $this->role = $role;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::role($this->role)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Username',
            'Email',
            'NIS',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No. Telepon',
            'Alamat',
            'Status',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->nama_lengkap,
            $user->username,
            $user->email,
            $user->nis,
            $user->jenis_kelamin,
            $user->tempat_lahir,
            $user->tanggal_lahir,
            $user->no_telepon,
            $user->alamat,
            $user->aktif ? 'Aktif' : 'Non-Aktif',
        ];
    }
}
