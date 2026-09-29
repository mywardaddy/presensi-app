<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsenTotalExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $date1;
    protected $date2;

    public function __construct($date1, $date2)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    public function view(): View
{
    $date1 = $this->date1;
    $date2 = $this->date2;

    $users = User::where('role_id', '2')->orderBy('name', 'ASC')->get();

    $rekap = [];

    foreach ($users as $user) {
        // Hitung jumlah hadir: harus ada entry_time dan out_time, dan description 'Hadir'
        $hadir = Absensi::where('user_id', $user->id)
            ->whereBetween('date', [$date1, $date2])
            ->where('description', 'Hadir')
            ->whereNotNull('entry_time')
            ->whereNotNull('out_time')
            ->count();

        // Hitung jumlah tidak hadir: description 'Tidak Hadir' atau absen pulang kosong (out_time null)
        $tidak_hadir = Absensi::where('user_id', $user->id)
            ->whereBetween('date', [$date1, $date2])
            ->where(function($query) {
                $query->where('description', 'Tidak Lengkap')
                    ->orWhereNull('out_time');
            })
            ->count();

        $rekap[] = [
            'nim' => $user->nim,
            'name' => $user->name,
            'prodi' => $user->prodi,
            'hadir' => $hadir,
            'tidak_hadir' => $tidak_hadir,
        ];
    }

    return view('pages.admin.absensi.ex-total', [
        'rekap' => $rekap,
        'date1' => $date1,
        'date2' => $date2,
    ]);
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
