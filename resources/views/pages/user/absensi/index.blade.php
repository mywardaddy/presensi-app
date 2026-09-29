@extends('layouts.admin')

@section('title')
    Absensi
@endsection

@section('container')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="clock"></i></div>
                            Absensi Masuk
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-4">
        <!-- Peta Lokasi -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">Lokasi Anda</div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Absensi -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">Absen Masuk</div>
                    <div class="card-body">
                        <div id="loading-overlay">
                            <div id="loader"></div>
                            <p id="loading-message">Sedang menyimpan data..</p>
                        </div>

                        @if ($cek_absensi == 1 && !$sudah_absen_pulang)
                            <div class="alert alert-info">Anda sudah melakukan absensi masuk. Silakan lanjutkan untuk absensi pulang.</div>
                        @endif

                        @if ($app->is_active == '0')
                            <div class="alert alert-warning">Saat ini anda tidak bisa mengisi absensi, karena absensi sedang dinonaktifkan oleh admin.</div>
                        @else
                            @if ($cek_absensi == 0)
                                <div class="alert alert-danger" id="alert-form" style="display: none;">
                                    Anda berada di luar radius yang ditentukan. Anda tidak bisa mengisi absensi.
                                </div>
                                <div class="alert alert-success" id="alert-form-success" style="display: none;">
                                    Anda berada di dalam radius yang ditentukan. Anda bisa mengisi absensi.
                                </div>
                                <div class="alert alert-danger" id="alert-error" style="display: none;"></div>

                                <div style="display: block" id="absensi">
                                    <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1">Nama</label>
                                                <input class="form-control" name="name" type="text" value="{{ Auth::user()->name }}" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Jenis Kelamin</label>
                                                <input class="form-control" name="gender" type="text" value="{{ Auth::user()->gender }}" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Jabatan</label>
                                                <input class="form-control" name="jabatan" type="text" value="{{ Auth::user()->position }}" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Program Studi</label>
                                                <input class="form-control" name="prodi" type="text" value="{{ Auth::user()->prodi }}" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Latitude</label>
                                                <input class="form-control" name="latitude" id="latitude" type="text" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Longitude</label>
                                                <input class="form-control" name="longitude" id="longitude" type="text" readonly />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Keterangan</label>
                                                <input class="form-control" name="description" value="Hadir" readonly />
                                            </div>
                                        </div>

                                        @include('includes.scan-face-user', ['user_id' => Auth::user()->id])
                                    </form>
                                </div>
                            @else
                                <script>
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Sudah Absen',
                                        text: 'Anda sudah mengisi absen hari ini.',
                                        confirmButtonColor: '#3085d6'
                                    });
                                </script>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('addon-style')
    <link href="{{ url('admin/css/loader.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.css" />
    <style>#map { height: 300px; }</style>
@endpush

@push('addon-script')
    <script src="{{ url('admin/js/loader.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                showConfirmButton: true
            });
        @endif

        // Leaflet Peta dan Lokasi
        let mymap = L.map('map').setView([{{ $instansi->latitude }}, {{ $instansi->longitude }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mymap);

        let referencePoint = L.latLng([{{ $instansi->latitude }}, {{ $instansi->longitude }}]);
        let radius = {{ $instansi->radius }};

        L.circle(referencePoint, {
            color: 'red', fillColor: '#f03', fillOpacity: 0.5, radius: radius
        }).addTo(mymap);

        L.marker(referencePoint).addTo(mymap).bindPopup("<b>{{ $instansi->name }}</b>").openPopup();

        function onLocationFound(e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;
            let distance = e.latlng.distanceTo(referencePoint);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (distance <= radius) {
                document.getElementById('absensi').style.display = 'block';
                document.getElementById('alert-form').style.display = 'none';
                document.getElementById('alert-form-success').style.display = 'block';
            } else {
                document.getElementById('absensi').style.display = 'none';
                document.getElementById('alert-form').style.display = 'block';
                document.getElementById('alert-form-success').style.display = 'none';
            }

            L.marker(e.latlng).addTo(mymap).bindPopup("Lokasi Anda").openPopup();
        }

        function onLocationError(e) {
            document.getElementById('absensi').style.display = 'none';
            let alert = document.getElementById('alert-error');
            alert.style.display = 'block';
            alert.innerHTML = e.message;
        }

        L.control.locate({
            setView: true,
            maxZoom: 16,
            enableHighAccuracy: true,
            showPopup: true,
            strings: { title: "Temukan Lokasi", popup: "Lokasi Anda" }
        }).addTo(mymap);

        mymap.on('locationfound', onLocationFound);
        mymap.on('locationerror', onLocationError);

        mymap.locate({
            setView: true,
            maxZoom: 16,
            enableHighAccuracy: true,
            timeout: 10000
        });
    </script>
@endpush
