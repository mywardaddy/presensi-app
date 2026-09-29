@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('container')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Dashboard
                        </h1>
                        <div class="page-header-subtitle">Administrator Panel</div>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">
                        <div class="input-group input-group-joined border-0" style="width: 16.5rem">
                            <span class="input-group-text"><i class="text-primary" data-feather="calendar"></i></span>
                            <input class="form-control ps-0 pointer" id="litepickerRangePlugin" placeholder="Select date range..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-xl-12 mb-4">
                <div class="card h-100">
                    <div class="card-body h-100 p-5">
                        <div class="row align-items-center">
                            <div class="col-xl-8 col-xxl-12">
                                <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                    <h1 class="text-primary">Selamat Datang {{ Auth::user()->name }}!</h1>
                                    <p class="text-gray-700 mb-0">Di Website Presensi Online 7 Karakter</p>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xxl-12 text-center">
                                <img class="img-fluid" src="/admin/assets/img/illustrations/at-work.svg" style="max-width: 26rem" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Kartu -->
        <div class="row">
            <!-- Kartu Total User -->
            <div class="col-lg-12 col-xl-6 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Total User</div>
                                <div class="text-lg fw-bold">{{ $user }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="users"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="{{ route('user.index') }}">Selengkapnya</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Kartu Kehadiran Hari Ini -->
            <div class="col-lg-12 col-xl-6 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Kehadiran Hari Ini</div>
                                <div class="text-lg fw-bold">{{ $hadir }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="log-in"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="{{ route('presensi.index') }}">Selengkapnya</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Tabel Data Kehadiran -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-header-actions mb-4">
                    <div class="card-header">
                        Daftar Absen Hari Ini
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="10" style="vertical-align: middle;">No.</th>
                                        <th style="vertical-align: middle;">NIM</th>
                                        <th style="vertical-align: middle;">Nama</th>
                                        <th style="vertical-align: middle;">Jabatan</th>
                                        <th style="vertical-align: middle;">Program Studi</th>
                                        <th style="vertical-align: middle;">Tanggal</th>
                                        <th style="vertical-align: middle;">Jam <br> Masuk</th>
                                        <th style="vertical-align: middle;">Jam <br> Pulang</th>
                                        <th style="vertical-align: middle;">Keterangan</th>
                                        <th style="vertical-align: middle;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @forelse ($in as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->user->nim ?? '' }}</td>
                                            <td>{{ $row->user->name ?? '' }}</td>
                                            <td>{{ $row->user->position ?? '' }}</td>
                                            <td>{{ $row->user->prodi ?? '' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                            <td>{{ $row->entry_time }}</td>
                                            <td>{{ $row->out_time ?? '-' }}</td>
                                            <td>
                                                @if ($row->entry_time && $row->out_time)
                                                    Hadir
                                                @else
                                                    Tidak Lengkap
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-primary btn-sm" href="{{ route('presensi.show', $row->id) }}">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data kehadiran hari ini</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
