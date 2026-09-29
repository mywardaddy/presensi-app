<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Absensi;

use App\Exports\AbsensiExport;

use Maatwebsite\Excel\Facades\Excel;

class ReportUserController extends Controller
{
    public function index(Request $request)
{
    $absensi = collect();
    $date1 = null;
    $date2 = null;
    $jumlah_hadir = 0;
    $jumlah_tidak_lengkap = 0;
    $searchPerformed = false;
    $user_id = Auth::user()->id;

    if ($request->has('search')) {
        $searchPerformed = true;
        $date1 = $request->date1;
        $date2 = $request->date2;

        $absensi = Absensi::with('user')
            ->where('user_id', $user_id)
            ->whereBetween('date', [$date1, $date2])
            ->orderBy('date', 'ASC')
            ->get();

        // Evaluasi setiap data absensi
        foreach ($absensi as $absen) {
            // Jika ada out_time dan description == 'Hadir', maka dihitung hadir
            if ($absen->description === 'Hadir' && $absen->out_time != null) {
                $jumlah_hadir++;
            } else {
                $jumlah_tidak_lengkap++;
            }
        }
    }

    return view('pages.user.report.index', [
        'absensi' => $absensi,
        'date1' => $date1,
        'date2' => $date2,
        'user_id' => $user_id,
        'searchPerformed' => $searchPerformed,
        'jumlah_hadir' => $jumlah_hadir,
        'jumlah_tidak_lengkap' => $jumlah_tidak_lengkap,
    ]);
}

    public function export_data($date1, $date2, $user_id){
        $export = new AbsensiExport($date1, $date2, $user_id);

        return Excel::download($export, 'Laporan Absensi Tanggal '.$date1.' sd '.$date2.'.xlsx');
    }
}