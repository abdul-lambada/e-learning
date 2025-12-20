<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user();

        $data = [
            'kelas' => $siswa->kelas()->first(),
        ];

        return view('siswa.dashboard', $data);
    }
}
