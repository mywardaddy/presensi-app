<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Exports\AbsensiExport;
use App\Exports\AbsenTotalExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportAdminController extends Controller
{
    public function index(Request $request)
    {
        $absensi = collect();
        $date1 = null;
        $date2 = null;
        $user_id = null;
        $jumlah_hadir = 0;
        $jumlah_tidak_lengkap = 0;
        $searchPerformed = false;

        if ($request->has('search')) {
            $searchPerformed = true;
            $date1 = $request->date1;
            $date2 = $request->date2;
            $user_id = $request->user_id;

            $query = Absensi::with('user')
                ->select('absensi.*')
                ->leftJoin('users', 'absensi.user_id', '=', 'users.id')
                ->whereBetween('absensi.date', [$date1, $date2]);

            if ($user_id !== 'all') {
                $query->where('absensi.user_id', $user_id);

                // Hitung jumlah hadir
                $jumlah_hadir = Absensi::where('user_id', $user_id)
                    ->whereBetween('date', [$date1, $date2])
                    ->whereNotNull('entry_time')
                    ->whereNotNull('out_time')
                    ->where('description', 'Hadir')
                    ->count();

                // Hitung jumlah tidak hadir
                $jumlah_tidak_lengkap = Absensi::where('user_id', $user_id)
                    ->whereBetween('date', [$date1, $date2])
                    ->where(function ($q) {
                        $q->whereNull('entry_time')
                            ->orWhereNull('out_time')
                            ->orWhere('description', '!=', 'Hadir');
                    })
                    ->count();
            }

            $absensi = $query->orderBy('users.name', 'ASC')->get();
        }

        $employes = User::where('role_id', '2')->orderBy('name', 'ASC')->get();

        return view('pages.admin.report.index', [
            'absensi' => $absensi,
            'date1' => $date1,
            'date2' => $date2,
            'user_id' => $user_id,
            'employes' => $employes,
            'jumlah_hadir' => $jumlah_hadir,
            'jumlah_tidak_lengkap' => $jumlah_tidak_lengkap,
            'searchPerformed' => $searchPerformed
        ]);
    }

    public function export_data($date1, $date2, $user_id)
    {
        $export = new AbsensiExport($date1, $date2, $user_id);
        return Excel::download($export, 'Laporan Absensi Tanggal ' . $date1 . ' sd ' . $date2 . '.xlsx');
    }

    public function laporan_total(Request $request)
    {
        $users = collect();
        $date1 = null;
        $date2 = null;
        $searchPerformed = false;

        if ($request->has('search')) {
            $searchPerformed = true;
            $date1 = $request->date1;
            $date2 = $request->date2;

            $users = User::where('role_id', '2')->orderBy('name', 'ASC')->get();

            foreach ($users as $user) {
                $attendanceCount = Absensi::where('user_id', $user->id)
                    ->whereBetween('date', [$date1, $date2])
                    ->whereNotNull('entry_time')
                    ->whereNotNull('out_time')
                    ->where('description', 'Hadir')
                    ->count();

                $user->attendance = $attendanceCount;
            }
        }

        return view('pages.admin.report.total', [
            'users' => $users,
            'date1' => $date1,
            'date2' => $date2,
            'searchPerformed' => $searchPerformed
        ]);
    }

    public function export_total($date1, $date2)
    {
        $export = new AbsenTotalExport($date1, $date2);
        return Excel::download($export, 'Laporan Total Absensi Tanggal ' . $date1 . ' sd ' . $date2 . '.xlsx');
    }
}
