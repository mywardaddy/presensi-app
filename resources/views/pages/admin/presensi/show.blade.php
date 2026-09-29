@extends('layouts.admin')

@section('title')
    {{ $title }}
@endsection

@section('container')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="clock"></i></div>
                            Detail Absensi
                        </h1>
                        <div class="page-header-subtitle">Informasi Lengkap Absensi</div>
                    </div>
                </div>
                <nav class="mt-4 rounded" aria-label="breadcrumb">
                    <ol class="breadcrumb px-3 py-2 rounded mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Absensi</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-header-actions mb-4">
                    <div class="card-header">
                        Detail Absensi
                        <div>
                            <a href="{{ route('presensi.index') }}" class="btn btn-sm btn-light">
                                <i data-feather="arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($item->description == 'Hadir')
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">Lokasi Absensi</div>
                                    <div class="card-body p-0">
                                        <div id="map" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="40%">NIM</th>
                                            <td>{{ $item->user->nim ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <td>{{ $item->user->position ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>{{ $item->user->prodi ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Tanggal</th>
                                            <td>{{ date('d F Y', strtotime($item->date)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($item->entry_time && $item->out_time)
                                                    <span class="badge bg-success">Hadir</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Lengkap</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Jam Masuk</th>
                                            <td>{{ $item->entry_time ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jam Pulang</th>
                                            <td>{{ $item->out_time ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($item->description == 'Hadir')
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="20%">Kegiatan</th>
                                            <td>{{ $item->activity ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Koordinat</th>
                                            <td>
                                                Latitude: {{ $item->latitude }}<br>
                                                Longitude: {{ $item->longitude }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if ($item->picture)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">Bukti Foto</div>
                                    <div class="card-body text-center">
                                        <a href="{{ Storage::url($item->picture) }}" target="_blank">
                                            <img src="{{ Storage::url($item->picture) }}" class="img-fluid rounded" style="max-height: 400px;" alt="Bukti Absensi">
                                        </a>
                                    </div>
                                </div>
                            </div>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@push('addon-script')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map only if attendance is present
        @if($item->description == 'Hadir')
            var mymap = L.map('map').setView([{{ $item->latitude }}, {{ $item->longitude }}], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);

            // Institution location marker
            L.marker([{{ $instansi->latitude }}, {{ $instansi->longitude }}])
                .addTo(mymap)
                .bindPopup('Lokasi Instansi');
            
            // Attendance location marker
            L.marker([{{ $item->latitude }}, {{ $item->longitude }}])
                .addTo(mymap)
                .bindPopup('Lokasi Absen')
                .openPopup();

            // Add radius circle around institution
            L.circle([{{ $instansi->latitude }}, {{ $instansi->longitude }}], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.2,
                radius: {{ $instansi->radius ?? 100 }}
            }).addTo(mymap);
        @endif

        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endpush