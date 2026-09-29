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
                            <div class="page-header-subtitle">User Panel</div>
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

            <div class="row">
                <!-- Absen Masuk -->
                <div class="col-lg-12 col-xl-6 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Absen Masuk</div>
                                    <div class="text-lg fw-bold">{{ $absensi->entry_time ?? '-' }}</div>
                                </div>
                                <i class="feather-xl text-white-50" data-feather="log-in"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Absen Pulang -->
                <div class="col-lg-12 col-xl-6 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Absen Pulang</div>
                                    <div class="text-lg fw-bold">{{ $absensi->out_time ?? '-' }}</div>
                                </div>
                                <i class="feather-xl text-white-50" data-feather="log-out"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Absensi -->
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Status Absensi</h4>
                            <p class="card-text">
                                @if($absensi->description == 'Hadir')
                                    Anda telah hadir dengan lengkap (Masuk dan Pulang).
                                @elseif($absensi->description == 'Tidak Lengkap - Belum Absen Pulang')
                                    Anda sudah absen masuk, namun belum absen pulang.
                                @else
                                    Absen anda tidak lengkap hari ini.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
