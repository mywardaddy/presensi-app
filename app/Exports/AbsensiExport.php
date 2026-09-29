<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class AbsensiExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $date1;
    protected $date2;
    protected $user_id;

    public function __construct($date1, $date2, $user_id)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
        $this->user_id = $user_id;
    }

    public function collection()
    {
        $query = Absensi::with('user')
            ->whereBetween('date', [$this->date1, $this->date2]);

        if ($this->user_id != 'all') {
            $query->where('user_id', $this->user_id);
        }

        $data = $query->get();

        // Grouping per user_id + date untuk menghitung hadir dan tidak hadir
        $grouped = $data->groupBy(function ($item) {
            return $item->user_id . '_' . $item->date;
        });

        $jumlah_hadir = 0;
        $jumlah_tidak_lengkap = 0;

        foreach ($grouped as $group) {
            // Cek ada record dengan status hadir lengkap (description = 'hadir' + entry_time & out_time tidak kosong)
            $adaHadir = $group->contains(function ($item) {
                return strtolower($item->description) === 'hadir'
                    && !empty($item->entry_time)
                    && !empty($item->out_time);
            });

            if ($adaHadir) {
                $jumlah_hadir++;
            } else {
                $jumlah_tidak_lengkap++;
            }
        }

        // Mapping semua data untuk export dengan keterangan disesuaikan:
        // jika description == 'hadir' tapi out_time kosong, status jadi 'Tidak Hadir'
        $rows = $data->map(function ($item) {
            $desc = strtolower($item->description);

            if ($desc === 'hadir') {
                if (!empty($item->entry_time) && !empty($item->out_time)) {
                    $status = 'Hadir';
                } else {
                    $status = 'Tidak Lengkap';
                }
            } else {
                $status = $item->description ?? '-';
            }

            return [
                $item->user->nim ?? '-',
                $item->user->name ?? '-',
                $item->user->position ?? '-',
                $item->user->prodi ?? '-',
                $item->date,
                $item->entry_time ?? '-',
                $item->out_time ?? '-',
                $item->notes ?? '-',
                $status,
            ];
        });

        // Tambahkan baris kosong dan total hadir / tidak hadir
        $rows->push(['', '', '', '', '', '', '', '', '']);
        $rows->push(['', '', '', '', '', '', '', 'Total Hadir', $jumlah_hadir]);
        $rows->push(['', '', '', '', '', '', '', 'Total Tidak Lengkap', $jumlah_tidak_lengkap]);

        return new Collection($rows);
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Jabatan',
            'Prodi',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Kegiatan',
            'Keterangan',
        ];
    }
}
