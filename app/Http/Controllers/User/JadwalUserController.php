<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalUserController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Jadwal::where('is_shared', true)->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->addColumn('jam', function ($row) {
                return $row->jam_mulai . ' - ' . $row->jam_selesai;
            })
            ->addColumn('kode_karakter', function ($row) {
                return $row->kode_karakter;
            })
            ->addColumn('nama_jadwal', function ($row) {
                return $row->nama_jadwal;
            })
            ->addColumn('deskripsi', function ($row) {
                return $row->deskripsi;
            })
            ->addColumn('opening', function ($row) {
                return $row->opening;
            })
            ->addColumn('narasumber', function ($row) {
                return $row->narasumber;
            })
            ->addColumn('moderator', function ($row) {
                return $row->moderator;
            })
            ->rawColumns(['jam']) // tambahkan kolom lain jika ada HTML
            ->make(true);
    }

    return view('pages.user.jadwal.index');
}

}
