<?php
use App\Models\PengaturanAplikasi;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/debug-settings', function () {
    $settings = PengaturanAplikasi::first();
    return response()->json([
        'settings' => $settings,
        'favicon_url_helper' => $settings->favicon ? Storage::url($settings->favicon) : 'default',
        'asset_url' => asset('storage/' . $settings->favicon)
    ]);
});
