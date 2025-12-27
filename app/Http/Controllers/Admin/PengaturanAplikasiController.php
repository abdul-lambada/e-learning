<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanAplikasiController extends Controller
{
    public function index()
    {
        $settings = PengaturanAplikasi::getSettings();
        return view('admin.pengaturan_aplikasi.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = PengaturanAplikasi::getSettings();

        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'email_kontak' => 'nullable|email',
            'logo_sekolah' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
        ]);

        $data = $request->except(['logo_sekolah', 'favicon']);

        if ($request->hasFile('logo_sekolah')) {
            if ($settings->logo_sekolah) {
                Storage::disk('public')->delete($settings->logo_sekolah);
            }
            $data['logo_sekolah'] = $request->file('logo_sekolah')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->update($data);

        return back()->with('success', 'Pengaturan sekolah berhasil diperbarui.');
    }
}
