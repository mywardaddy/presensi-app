@extends('layouts.admin')

@section('title')
    Laporan
@endsection

@section('container')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="file-text"></i></div>
                                Laporan Absensi
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header">
                            Laporan Absensi Mahasiswa
                        </div>
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('report.index') }}" method="GET">
                                @csrf
                                <div class="col-3">
                                    <input type="date" class="form-control" id="date1" name="date1" required>
                                </div>
                                <div class="col-3">
                                    <input type="date" class="form-control" id="date2" name="date2" required>
                                </div>
                                <div class="col-3">
                                    <select name="user_id" id="user_id" class="form-control selectx" required>
                                        <option value="">Pilih User...</option>
                                        <option value="all">Semua User</option>
                                        @foreach ($employes as $employe)
                                            <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" name="search" class="btn btn-primary mb-3">Cari</button>
                                </div>
                            </form>

                            @if ($searchPerformed)
                                <div class="mb-3 text-end">
                                    <a class="btn btn-sm btn-success" href="{{ route('export-data-absensi', [$date1, $date2, $user_id]) }}">
                                        <i data-feather="download"></i>&nbsp; Download Excel
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Jabatan</th>
                                                <th>Program Studi</th>
                                                <th>Tanggal</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Kegiatan</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @forelse ($absensi as $absen)
                                                <tr class="text-center">
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $absen->user->nim ?? '-' }}</td>
                                                    <td>{{ $absen->user->name ?? '-' }}</td>
                                                    <td>{{ $absen->user->position ?? '-' }}</td>
                                                    <td>{{ $absen->user->prodi ?? '-' }}</td>
                                                    <td>{{ $absen->date ? date('d-m-Y', strtotime($absen->date)) : '-' }}</td>
                                                    <td>{{ $absen->entry_time ?? '-' }}</td>
                                                    <td>{{ $absen->out_time ?? '-' }}</td>
                                                    <td>{{ $absen->notes ?? '-' }}</td>
                                                    <td>
                                                        @if ($absen->entry_time && !$absen->out_time)
                                                            Tidak Lengkap
                                                        @else
                                                            {{ $absen->description ?? '-' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">Data tidak ditemukan</td>
                                                </tr>
                                            @endforelse
                                            @if ($user_id != 'all')
                                                <tr>
                                                    <th colspan="9" class="text-end">Total Hadir</th>
                                                    <td class="text-center">{{ $jumlah_hadir }}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="9" class="text-end">Total Tidak Lengkap</th>
                                                    <td class="text-center">{{ $jumlah_tidak_lengkap }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </main>
@endsection

@push('addon-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
@endpush

@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(".selectx").select2({
            theme: "bootstrap-5",
            width: "100%",
        });
    </script>
@endpush
