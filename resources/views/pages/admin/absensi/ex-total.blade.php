<table class="table table-bordered table-striped table-sm">
    <thead>
        <tr>
            <th style="vertical-align: middle;">No.</th>
            <th style="vertical-align: middle;">NIM</th>
            <th style="vertical-align: middle;">Nama</th>
            <th style="vertical-align: middle;">Program Studi</th>
            <th style="vertical-align: middle;" class="text-center">Jumlah Hadir</th>
            <th style="vertical-align: middle;" class="text-center">Jumlah Tidak Lengkap</th>
            <th style="vertical-align: middle;">Tanggal Awal</th>
            <th style="vertical-align: middle;">Tanggal Akhir</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($rekap as $data)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data['nim'] }}</td>
                <td>{{ $data['name'] }}</td>
                <td>{{ $data['prodi'] }}</td>
                <td class="text-center">{{ $data['hadir'] }}</td>
                <td class="text-center">{{ $data['tidak_lengkap'] }}</td>
                <td>{{ date('d-m-Y', strtotime($date1)) }}</td>
                <td>{{ date('d-m-Y', strtotime($date2)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
