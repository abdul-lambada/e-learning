<?php

namespace App\Imports;

use App\Models\SoalKuis;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SoalKuisImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $kuis_id;
    protected $lastNo;

    public function __construct($kuis_id)
    {
        $this->kuis_id = $kuis_id;
        $this->lastNo = SoalKuis::where('kuis_id', $kuis_id)->max('nomor_soal') ?? 0;
    }

    public function model(array $row)
    {
        $this->lastNo++;

        return new SoalKuis([
            'kuis_id'      => $this->kuis_id,
            'nomor_soal'   => $this->lastNo,
            'tipe_soal'    => strtolower($row['tipe_soal']),
            'pertanyaan'   => $row['pertanyaan'],
            'pilihan_a'    => $row['pilihan_a'] ?? null,
            'pilihan_b'    => $row['pilihan_b'] ?? null,
            'pilihan_c'    => $row['pilihan_c'] ?? null,
            'pilihan_d'    => $row['pilihan_d'] ?? null,
            'pilihan_e'    => $row['pilihan_e'] ?? null,
            'kunci_jawaban' => $row['kunci_jawaban'] ?? null,
            'bobot_nilai'  => $row['bobot_nilai'] ?? 0,
            'pembahasan'   => $row['pembahasan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'tipe_soal' => 'required|in:pilihan_ganda,essay,Pilihan_ganda,Essay',
            'pertanyaan' => 'required',
            'bobot_nilai' => 'required|numeric',
        ];
    }
}
