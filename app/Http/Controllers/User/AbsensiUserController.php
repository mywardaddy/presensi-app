<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AbsensiRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Absensi;
use App\Models\Instansi;
use App\Models\Application;

use Intervention\Image\Facades\Image;

class AbsensiUserController extends Controller
{
    // Menampilkan halaman absensi (absensi masuk)
    public function index()
    {
        $user_id = Auth::user()->id;
        $today = date('Y-m-d');

        // Ambil absensi hari ini
        $absensi = Absensi::where([
            ['user_id', '=', $user_id],
            ['date', '=', $today]
        ])->first();

        // Cek apakah user sudah absen masuk
        $cek_absensi = $absensi ? 1 : 0;

        // Cek apakah sudah absen pulang
        $sudah_absen_pulang = $absensi && $absensi->out_time !== null;

        // Data instansi dan aplikasi
        $instansi = Instansi::first();
        $app = Application::first();

        return view('pages.user.absensi.index', [
            'cek_absensi' => $cek_absensi,
            'sudah_absen_pulang' => $sudah_absen_pulang,
            'instansi' => $instansi,
            'app' => $app
        ]);
    }

    // Menyimpan absensi masuk
    public function store(AbsensiRequest $request)
    {
        $validatedData = $request->all();

        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['date'] = date("Y-m-d");
        $validatedData['entry_time'] = date("H:i:s");
        $validatedData['description'] = 'Hadir'; // Status hadir

        // Upload foto
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');

            // Resize dan kompres
            $compressedImage = Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg', 75);

            $path = 'assets/presensi/' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($path, (string) $compressedImage->encode());

            $validatedData['picture'] = $path;
        }

        Absensi::create($validatedData);

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Sukses! Absensi anda Berhasil Disimpan');
    }

    // Memperbarui absensi pulang
    public function update(Request $request, $id)
    {
        $model = Absensi::findOrFail($id);

        $model->out_time = $request->out_time;

        // Update status kehadiran
        $model->description = $model->out_time ? 'Hadir' : 'Tidak Lengkap';
        $model->notes = $request->notes;
        $model->save();

        return redirect()
            ->route('absensi-pulang')
            ->with('success', 'Sukses! Absensi anda Berhasil Disimpan');
    }

    // Halaman absensi pulang
    public function get_back()
    {
        $user_id = Auth::user()->id;
        $today = date('Y-m-d');

        $cek_absensi = Absensi::where([
            ['user_id', '=', $user_id],
            ['date', '=', $today]
        ])->count();

        $cek_pulang = Absensi::where([
            ['user_id', '=', $user_id],
            ['date', '=', $today],
            ['out_time', '=', null],
            ['description', '=', 'Hadir']
        ])->count();

        $instansi = Instansi::first();
        $app = Application::first();
        $item = Absensi::where('user_id', $user_id)->latest()->first();

        return view('pages.user.absensi.go-back', [
            'cek_absensi' => $cek_absensi,
            'cek_pulang' => $cek_pulang,
            'instansi' => $instansi,
            'item' => $item,
            'app' => $app
        ]);
    }
}
