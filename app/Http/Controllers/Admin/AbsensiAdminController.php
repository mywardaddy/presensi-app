<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Absensi;
use App\Models\Instansi;
use Yajra\DataTables\Facades\DataTables;

class AbsensiAdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $today = date('Y-m-d');

            $query = Absensi::with('user')->latest();

            // Filter tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            } else {
                $query->where('date', $today);
            }

            // Filter keterangan
            if ($request->filled('description')) {
                $description = strtolower(trim($request->description));
                if ($description === 'hadir') {
                    $query->whereNotNull('out_time');
                } elseif ($description === 'tidak lengkap') {
                    $query->whereNull('out_time');
                }
                // Jika 'all' atau kosong, abaikan filter
            }

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-cog"></i>
                            </button>
                            <div class="dropdown-menu animated--fade-in-up">
                                <a class="dropdown-item" href="' . route('presensi.show', $item->id) . '">Detail</a>
                                <form action="' . route('presensi.destroy', $item->id) . '" method="POST" id="deleteForm'.$item->id.'">
                                    ' . method_field('delete') . csrf_field() . '
                                    <button class="dropdown-item btn-delete" data-form-id="deleteForm'.$item->id.'">Hapus</button>
                                </form>
                            </div>
                        </div>';
                })
                ->editColumn('date', fn($item) => date('d-m-Y', strtotime($item->date)))
                ->editColumn('out_time', fn($item) => $item->out_time ?? '-')
                ->editColumn('description', function ($item) {
                    if (is_null($item->out_time)) {
                        return '<span class="badge bg-danger">Tidak Lengkap</span>';
                    } else {
                        return '<span class="badge bg-success">Hadir</span>';
                    }
                })
                ->editColumn('picture', function ($item) {
                    if ($item->picture && Storage::disk('public')->exists($item->picture)) {
                        // Foto absensi
                        $src = Storage::url($item->picture);
                    } elseif ($item->user && $item->user->profile && file_exists(public_path('gambarprofile/' . $item->user->profile))) {
                        // Foto profil user
                        $src = asset('gambarprofile/' . $item->user->profile);
                    } else {
                        // Avatar default
                        $name = urlencode(optional($item->user)->name ?? 'Tidak Diketahui');
                        $src = "https://ui-avatars.com/api/?name=$name";
                    }

                    return '
                        <div class="d-flex align-items-center">
                            <div class="avatar me-2">
                                <img class="avatar-img img-fluid rounded-circle" src="'.$src.'" width="80" height="80"/>
                            </div>
                        </div>';
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action', 'picture', 'description']) // penting agar HTML tidak di-escape
                ->make(true);
        }

        return view('pages.admin.presensi.index');
    }

    public function show($id)
    {
        $item = Absensi::with('user')->findOrFail($id);
        $instansi = Instansi::first();
        $title = 'Detail Absensi';

        return view('pages.admin.presensi.show', compact('item', 'instansi', 'title'));
    }

    public function destroy($id)
    {
        $item = Absensi::findOrFail($id);

        if ($item->picture && Storage::disk('public')->exists($item->picture)) {
            Storage::disk('public')->delete($item->picture);
        }

        $item->delete();

        return redirect()
            ->route('presensi.index')
            ->with('success', 'Sukses! 1 Data Berhasil Dihapus');
    }

    public function reset_data(Request $request)
    {
        $request->validate([
            'date1' => 'required|date',
            'date2' => 'required|date|after_or_equal:date1',
            'description' => 'required|string'
        ]);

        $date1 = $request->date1;
        $date2 = $request->date2;
        $category = strtolower($request->description);

        // Hapus file foto di periode tersebut
        $absensi = Absensi::whereBetween('date', [$date1, $date2])->get();
        foreach ($absensi as $item) {
            if ($item->picture && Storage::disk('public')->exists($item->picture)) {
                Storage::disk('public')->delete($item->picture);
            }
        }

        // Hapus data absensi jika kategori bukan 'foto' saja
        if ($category !== 'foto') {
            Absensi::whereBetween('date', [$date1, $date2])->delete();
        }

        return redirect()
            ->route('presensi.index')
            ->with('success', 'Sukses! Data berhasil direset');
    }
}
