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
                                Absensi Pulang
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Lokasi -->
                    <div class="card mb-4">
                        <div class="card-header">Lokasi Anda</div>
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <!-- Absen Pulang -->
                    <div class="card mb-4">
                        <div class="card-header">Absen Pulang</div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show hide-alert" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div id="loading-overlay">
                                <div id="loader"></div>
                                <p id="loading-message">Sedang menyimpan data..</p>
                            </div>

                            @if ($app->is_active == '0')
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Saat ini anda tidak bisa mengisi absensi, karena absensi sedang di nonaktifkan oleh admin.
                                </div>
                            @else
                                @if ($cek_absensi > 0)
                                    @if ($cek_pulang > 0)
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-form" style="display: none;">
                                            Anda berada di luar radius yang ditentukan. Anda tidak bisa mengisi absensi.
                                        </div>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-form-success" style="display: none;">
                                            Anda berada di dalam radius yang ditentukan. Anda bisa mengisi absensi.
                                        </div>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-error" style="display: none;"></div>

                                        <div style="display: block" id="absensi">
                                            <form action="{{ route('absensi.update', $item->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Nama</label>
                                                        <input class="form-control" name="name" type="text" value="{{ Auth::user()->name }}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Jenis Kelamin</label>
                                                        <input class="form-control" name="gender" type="text" value="{{ Auth::user()->gender }}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Jabatan</label>
                                                        <input class="form-control" name="jabatan" type="text" value="{{ Auth::user()->position }}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Program Studi</label>
                                                        <input class="form-control" name="prodi" type="text" value="{{ Auth::user()->prodi }}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Lattitude</label>
                                                        <input class="form-control" name="latitude" id="latitude" type="text" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Longitude</label>
                                                        <input class="form-control" name="longitude" id="longitude" type="text" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Jam Pulang</label>
                                                        <input class="form-control" name="out_time" id="out_time" value="{{ date('H:i:s') }}" type="text" readonly/>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="small mb-1">Kegiatan Selama di Kampus</label>
                                                        <textarea name="notes" id="notes" class="form-control"></textarea>
                                                    </div>
                                                </div>

                                                <button class="btn btn-primary" type="submit">
                                                    Simpan Absen
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            Anda sudah mengisi absen hari ini.
                                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        Anda belum mengisi absen masuk, silahkan isi absen masuk terlebih dahulu.
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
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
    <style>
        #map { height: 300px; }
    </style>
@endpush

@push('addon-script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Loader & Map -->
    <script src="{{ url('admin/js/loader.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>

    <!-- Peta & Lokasi -->
    <script>
        let mymap = L.map('map').setView([{{ $instansi->latitude }}, {{ $instansi->longitude }}], {{ $instansi->radius }});

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);

        let referencePoint = L.latLng([{{ $instansi->latitude }}, {{ $instansi->longitude }}]);
        let radius = {{ $instansi->radius }};

        L.circle(referencePoint, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(mymap);

        L.marker(referencePoint).addTo(mymap)
            .bindPopup("<b>{{ $instansi->name }}</b>").openPopup();

        function onLocationFound(e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;
            let distance = e.latlng.distanceTo(referencePoint);

            if (distance <= radius) {
                document.getElementById('absensi').style.display = 'block';
                document.getElementById('alert-form').style.display = 'none';
                document.getElementById('alert-form-success').style.display = 'block';
            } else {
                document.getElementById('absensi').style.display = 'none';
                document.getElementById('alert-form').style.display = 'block';
                document.getElementById('alert-form-success').style.display = 'none';
            }

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            L.marker(e.latlng).addTo(mymap)
                .bindPopup("Lokasi Anda").openPopup();
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
            strings: {
                title: "Temukan lokasimu",
                popup: "Anda berada dalam jarak {distance} {unit} dari titik ini",
                metersUnit: "meter",
                feetUnit: "feet",
                outsideMapBoundsMsg: "Anda berada di luar batas peta"
            }
        }).addTo(mymap);

        mymap.on('locationfound', onLocationFound);
        mymap.on('locationerror', onLocationError);
        mymap.locate({
            setView: true,
            maxZoom: 16,
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        });
    </script>

<script>
document.getElementById('form-data').addEventListener('submit', function(e) {
    let notes = document.getElementById('notes').value.trim();

    // Jika kegiatan belum diisi
    if (notes === '') {
        e.preventDefault(); 
        document.getElementById('loading-overlay').style.display = 'none';
        
        Swal.fire({
            icon: 'warning',
            title: 'Kegiatan belum diisi!',
            text: 'Silakan isi kegiatan selama di kampus sebelum absen pulang.',
            confirmButtonText: 'OK'
        });
    } 
    // Jika kegiatan terisi
    else {
        // Tampilkan loader
        document.getElementById('loading-overlay').style.display = 'flex';
    }
});
</script>

        <!-- SweetAlert Success Notification -->
    @if (session()->has('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
        
    @endif
@endpush