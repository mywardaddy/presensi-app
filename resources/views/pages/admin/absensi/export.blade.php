<table class="table table-bordered table-striped table-sm">
    <thead>
        <tr>
            <th style="vertical-align: middle;">No.</th>
            <th style="vertical-align: middle;">NIM</th>
            <th style="vertical-align: middle;">Nama</th>
            <th style="vertical-align: middle;">Jabatan</th>
            <th style="vertical-align: middle;">Program Studi</th>
            <th style="vertical-align: middle;">Tanggal</th>
            <th style="vertical-align: middle;">Jam Masuk/Input</th>
            <th style="vertical-align: middle;">Jam Pulang</th>
            <th style="vertical-align: middle;">Kegiatan</th>
            <th style="vertical-align: middle;" class="text-center">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @forelse ($absensi as $absen)
            <tr>
                <td>{{ $no++; }}</td>
                <td>{{ $absen->user->nim ?? '' }}</td>
                <td>{{ $absen->user->name ?? '' }}</td>
                <td>{{ $absen->user->position ?? '' }}</td>
                <td>{{ $absen->user->prodi ?? '' }}</td>
                <td>{{ date('d-m-Y', strtotime($absen->date)) }}</td>
                <td>{{ $absen->entry_time }}</td>
                <td>{{ $absen->out_time }}</td>
                <td>{{ $absen->notes }}</td>
                <td class="text-center">{{ $absen->description }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">Data tidak ditemukan</td>
            </tr>
        @endforelse
        @if ($user_id != 'all')
            <tr>
                <th colspan="9">Total Kehadiran</th>
                <td class="text-center">{{ $jumlah_hadir }}</td>
            </tr>
        @endif
    </tbody>
</table>