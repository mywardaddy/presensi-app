<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;

class DashboardUserController extends Controller
{
    public function index()
{
    $user_id = Auth::user()->id;
    $today = date('Y-m-d');

    // Daftar semua absensi hari ini
    $in = Absensi::with(['user'])->where('date', $today)->latest()->get();

    // Ambil absensi user hari ini
    $absensi = Absensi::where('user_id', $user_id)
        ->where('date', $today)
        ->latest()
        ->first();

    // Logika status kehadiran
    if ($absensi && $absensi->entry_time && $absensi->out_time) {
        $absensi->description = 'Hadir';
    } elseif ($absensi && $absensi->entry_time && !$absensi->out_time) {
        $absensi->description = 'Tidak Lengkap - Belum Absen Pulang';
    } else {
        // Buat dummy object jika absensi belum ada agar tidak error di Blade
        if (!$absensi) {
            $absensi = new \stdClass();
            $absensi->entry_time = '-';
            $absensi->out_time = '-';
        } else {
            $absensi->entry_time = $absensi->entry_time ?? '-';
            $absensi->out_time = $absensi->out_time ?? '-';
        }

        $absensi->description = 'Tidak Lengkap';
    }

    return view('pages.user.dashboard', [
        'in' => $in,
        'absensi' => $absensi,
    ]);
}
}