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
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header">
                            Laporan Absensi Total
                        </div>
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('laporan-total') }}" method="GET">
                                @csrf
                                <div class="col-3">
                                    <label for="date1" class="visually-hidden"></label>
                                    <input type="date" class="form-control" id="date1" name="date1" required>
                                </div>
                                <div class="col-3">
                                    <label for="date2" class="visually-hidden"></label>
                                    <input type="date" class="form-control" id="date2" name="date2" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" name="search" class="btn btn-primary mb-3">Cari</button>
                                </div>
                            </form>

                            @if ($searchPerformed)
                                <div class="mb-3 text-end">
                                    <a class="btn btn-sm btn-success" href="{{ route('export-total-absensi', [$date1, $date2]) }}">
                                        <i data-feather="download"></i>&nbsp; Download Excel
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align: middle; text-align: center;">No.</th>
                                                <th style="vertical-align: middle; text-align: center;">NIM</th>
                                                <th style="vertical-align: middle; text-align: center;">Nama</th>
                                                <th style="vertical-align: middle; text-align: center;">Jabatan</th>
                                                <th style="vertical-align: middle; text-align: center;">Program Studi</th>
                                                <th style="vertical-align: middle; text-align: center;">Tanggal Awal</th>
                                                <th style="vertical-align: middle; text-align: center;">Tanggal Akhir</th>
                                                <th style="vertical-align: middle; text-align: center;">Jumlah Hadir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
    @php $no = 1; @endphp
    @forelse ($users as $user)
        <tr>
            <td style="text-align: center;">{{ $no++ }}</td>
            <td style="text-align: center;">{{ $user->nim }}</td>
            <td style="text-align: center;">{{ $user->name }}</td>
            <td style="text-align: center;">{{ $user->position }}</td>
            <td style="text-align: center;">{{ $user->prodi }}</td>
            <td style="text-align: center;">{{ date('d-m-Y', strtotime($date1)) }}</td>
            <td style="text-align: center;">{{ date('d-m-Y', strtotime($date2)) }}</td>
            <td style="text-align: center;">{{ $user->attendance ?? 0 }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Data tidak ditemukan</td>
        </tr>
    @endforelse
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


