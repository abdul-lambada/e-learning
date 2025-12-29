<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = new User([
            'nama_lengkap'  => $row['nama_lengkap'],
            'username'      => $row['username'],
            'email'         => $row['email'],
            'password'      => Hash::make($row['password'] ?? 'password123'),
            'nis'           => $row['nis'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'no_telepon'    => $row['no_telepon'],
            'alamat'        => $row['alamat'],
            'peran'         => 'siswa',
            'aktif'         => true,
        ]);

        $user->assignRole('siswa');

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'nis'          => 'nullable|string|unique:users,nis',
        ];
    }
}
