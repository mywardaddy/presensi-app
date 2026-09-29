<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        // Ambil semua ID mahasiswa
        $mahasiswaIds = User::where('role_id', '2')->pluck('id');

        // Hitung total mahasiswa
        $user = $mahasiswaIds->count();

        // Hitung mahasiswa yang hadir (sudah absen masuk dan pulang)
        $hadir = Absensi::where('date', $today)
                        ->whereNotNull('entry_time')
                        ->whereNotNull('out_time')
                        ->whereIn('user_id', $mahasiswaIds)
                        ->count();

        // Hitung mahasiswa yang tidak hadir
        $tidakLengkap = $user - $hadir;

        // Ambil data absensi hari ini untuk ditampilkan di tabel
        $in = Absensi::with(['user'])
                    ->where('date', $today)
                    ->latest()
                    ->get();

        return view('pages.admin.dashboard', [
            'user' => $user,
            'hadir' => $hadir,
            'tidakLengkap' => $tidakLengkap,
            'in' => $in
        ]);
    }
}
