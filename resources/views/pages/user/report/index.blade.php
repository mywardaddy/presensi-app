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
                                Laporan
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
                            Laporan Absensi
                        </div>
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('report-user.index') }}" method="GET">
                                @csrf
                                <div class="col-auto">
                                    <label for="date1" class="visually-hidden"></label>
                                    <input type="date" class="form-control" id="date1" name="date1" required>
                                </div>
                                <div class="col-auto">
                                    <label for="date2" class="visually-hidden"></label>
                                    <input type="date" class="form-control" id="date2" name="date2" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" name="search" class="btn btn-primary mb-3">Cari</button>
                                </div>
                            </form>

                            @if ($searchPerformed)
                                <div class="mb-3 text-end">
                                    <a class="btn btn-sm btn-success" href="{{ route('export-absensi-user', [$date1, $date2, $user_id]) }}">
                                        <i data-feather="download"></i>&nbsp; Download Excel
                                    </a>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align: middle;">No.</th>
                                                <th style="vertical-align: middle;">NIM</th>
                                                <th style="vertical-align: middle;">Nama</th>
                                                <th style="vertical-align: middle;">Jabatan</th>
                                                <th style="vertical-align: middle;">Program Studi</th>
                                                <th style="vertical-align: middle;">Tanggal</th>
                                                <th style="vertical-align: middle;">Jam Masuk</th>
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
                                                    <td class="text-center">
                                                        @if($absen->description === 'Hadir' && $absen->out_time != null)
                                                            <span class="badge bg-success">Hadir</span>
                                                        @else
                                                            <span class="badge bg-danger">Tidak Lengkap</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">Data tidak ditemukan</td>
                                                </tr>
                                            @endforelse
                                            @if (count($absensi) > 0)
                                                <tr>
                                                    <th colspan="9">Total Hadir</th>
                                                    <td class="text-center">{{ $jumlah_hadir }}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="9">Total Tidak Lengkap</th>
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



