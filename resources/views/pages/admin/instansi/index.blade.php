@extends('layouts.admin')

@section('title')
    Data Instansi
@endsection

@section('container')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                                Data Instansi
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">

                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show hide-alert" role="alert">
                    {{ session('success') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">Data Instansi Universitas</div>
                        <div class="card-body">
                            <form action="{{ route('instansi.update', $instansi->id) }}" method="post" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="name">Nama Instansi</label>
                                        <input class="form-control" name="name" id="name" value="{{ $instansi->name }}" type="text"/>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="name">Alamat</label>
                                        <textarea name="address" id="address" class="form-control">{{ $instansi->address }}</textarea>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="leader_name">Nama Pimpinan</label>
                                        <input class="form-control" name="leader_name" id="leader_name" type="text" value="{{ $instansi->leader_name }}"/>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="radius">Radius</label>
                                        <input class="form-control" name="radius" id="radius" type="text" value="{{ $instansi->radius }}"/>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="latitude">Lattitude</label>
                                        <input class="form-control" name="latitude" id="latitude" type="text" value="{{ $instansi->latitude }}"/>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label class="small mb-1" for="longitude">longitude</label>
                                        <input class="form-control" name="longitude" id="longitude" type="text" value="{{ $instansi->longitude }}"/>
                                    </div>
                                </div>
                                <!-- Submit button-->
                                <button class="btn btn-primary" type="submit">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">Map</div>
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('addon-style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.css" />
    <style>
        #map { height: 300px; }
    </style>
@endpush

@push('addon-script')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script>
        // let mymap = L.map('map').setView([{{ $instansi->latitude }}, {{ $instansi->longitude }}], {{ $instansi->radius }}); 

        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        // }).addTo(mymap);

        // let marker = L.marker([{{ $instansi->latitude }}, {{ $instansi->longitude }}]).addTo(mymap);
        $(function() {

            var curLocation = [0, 0];

            if (curLocation[0] == 0 && curLocation[1] == 0) {
                curLocation = [{{ $instansi->latitude }}, {{ $instansi->longitude }}];
            }

            let map = L.map('map').setView([{{ $instansi->latitude }}, {{ $instansi->longitude }}], {{ $instansi->radius }}); 

            var circle = L.circle([{{ $instansi->latitude }}, {{ $instansi->longitude }}], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 50
            }).addTo(map);

            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.attributionControl.setPrefix(false);

            var marker = new L.marker(curLocation, {
                draggable: 'true'
            });

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                marker.setLatLng(position, {
                draggable: 'true'
                }).bindPopup(position).update();
                $("#latitude").val(position.lat);
                $("#longitude").val(position.lng).keyup();
            });

            $("#latitude, #longitude").change(function() {
                var position = [parseInt($("#latitude").val()), parseInt($("#longitude").val())];
                marker.setLatLng(position, {
                draggable: 'true'
                }).bindPopup(position).update();
                map.panTo(position);
            });

            L.control.locate({
                setView: true,
                maxZoom: 16,
                enableHighAccuracy: true,
                showPopup: true,
                strings: {
                    title: "Temukan lokasimu",
                    popup: "Lokasi Anda",
                    metersUnit: "meter",
                    feetUnit: "feet",
                    outsideMapBoundsMsg: "Anda berada di luar batas area"
                }
            }).addTo(map);

            function onLocationFound(e) {
                let userLatLng = e.latlng;
                let userRadius = e.accuracy;
                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

            }

            function onLocationError(e) {

                console.log(e.message);
            }

            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);
            map.locate({
                setView: true, 
                maxZoom: 16, 
                enableHighAccuracy: true, 
                timeout: 10000, 
                maximumAge: 0 
            });

            map.addLayer(marker);
        })
    </script>
@endpush





