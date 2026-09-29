<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        // Deteksi apakah route saat ini admin atau user
        $isAdmin = str_contains($request->url(), '/admin');

        // Jika admin, tampilkan semua data
        $query = $isAdmin
            ? Jadwal::latest()
            : Jadwal::where('is_shared', true)->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jam', function ($row) {
                return $row->jam_mulai . ' - ' . $row->jam_selesai;
            })
            ->editColumn('tanggal', function ($row) {
                try {
                    return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    return '-';
                }
            })
            ->addColumn('action', function ($row) use ($isAdmin) {
                if ($isAdmin) {
                    $edit = route('jadwal.edit', $row->id);
                    $delete = route('jadwal.destroy', $row->id);
                    return '
                        <a href="' . $edit . '" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-delete" data-url="' . $delete . '">Hapus</button>
                    ';
                } else {
                    return '<a href="#" class="btn btn-primary btn-sm">Lihat</a>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Tentukan view berdasarkan role / path
    if (str_contains($request->url(), '/admin')) {
        return view('pages.admin.jadwal.index');
    } else {
        return view('pages.user.jadwal.index');
    }
}

    public function create()
    {
        return view('pages.admin.jadwal.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_karakter' => 'required|string|size:7',
            'nama_jadwal'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'opening'       => 'nullable|string',
            'narasumber'    => 'nullable|string',
            'moderator'     => 'nullable|string',
            'tanggal'       => 'required|date',
            'jam_mulai'     => 'required',
            'jam_selesai'   => 'required|after_or_equal:jam_mulai',
        ]);

        $validated['is_shared'] = $request->has('is_shared');
        $validated['user_id'] = auth()->id();

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('pages.admin.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'kode_karakter' => 'required|string|size:7',
            'nama_jadwal'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'opening'       => 'nullable|string',
            'narasumber'    => 'nullable|string',
            'moderator'     => 'nullable|string',
            'tanggal'       => 'required|date',
            'jam_mulai'     => 'required',
            'jam_selesai'   => 'required|after_or_equal:jam_mulai',
        ]);

        $validated['is_shared'] = $request->has('is_shared');

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function users()
{
    return $this->belongsToMany(User::class);
}
}
