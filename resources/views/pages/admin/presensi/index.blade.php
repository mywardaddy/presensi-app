@extends('layouts.admin')

@section('title')
    Daftar Absensi 7 Karakter
@endsection

@section('container')
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="clock"></i>
                                </div>
                                Daftar Absensi 7 Karakter
                            </h1>
                            <div class="page-header-subtitle">Daftar Absensi 7 Karakter</div>
                        </div>
                    </div>
                    <nav class="mt-4 rounded" aria-label="breadcrumb">
                        <ol class="breadcrumb px-3 py-2 rounded mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="">Master Data</a></li>
                            <li class="breadcrumb-item active">Daftar Absensi 7 Karakter</li>
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
                            Daftar Absensi
                            <div>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#resetModal">
                                    <i data-feather="refresh-cw"></i>&nbsp; Reset Data
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show hide-alert" role="alert">
                                    {{ session('success') }}
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
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
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">Tanggal Mulai:</label>
                                    <input class="form-control" name="start_date" id="start_date" type="date"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">Tanggal Akhir:</label>
                                    <input class="form-control" name="end_date" id="end_date" type="date"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">Keterangan:</label>
                                    <select name="description" id="description" class="form-control selectx" required>
                                        <option value="">Pilih..</option>
                                        <option value="all">Semua</option>
                                        <option value="Hadir">Hadir</option>
                                        <option value="Tidak Lengkap">Tidak Lengkap</option>
                                </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="form-label"></label>
                                    <button type="submit" class="btn btn-primary w-100 mt-1 filter">Filter</button>
                                </div>
                            </div>
                            {{-- List Data --}}
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th style="vertical-align: middle;" class="text-center">No.</th>
                                            <th style="vertical-align: middle;">NIM</th>
                                            <th style="vertical-align: middle;">Nama</th>
                                            <th style="vertical-align: middle;">Jabatan</th>
                                            <th style="vertical-align: middle;">Program Studi</th>
                                            <th style="vertical-align: middle;">Tanggal</th>
                                            <th style="vertical-align: middle;">Jam <br> Masuk</th>
                                            <th style="vertical-align: middle;">Jam <br> Pulang</th>
                                            <th style="vertical-align: middle;">Keterangan</th>
                                            <th style="vertical-align: middle;">Foto</th>
                                            <th style="vertical-align: middle;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </main>
    <!-- Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reset Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('reset-data') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="col-md-12">
                                <label class="small mb-1" for="date1">Tanggal Mulai</label>
                                <input class="form-control" name="date1" id="date1" type="date"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-md-12">
                                <label class="small mb-1" for="date2">Tanggal Akhir</label>
                                <input class="form-control" name="date2" id="date2" type="date"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-md-12">
                                <label class="small mb-1" for="category">Kategori</label>
                                <select name="description" id="description" class="form-control selectx" required>
                                    <option value="">Pilih..</option>
                                    <option value="all">Semua</option>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Tidak Lengkap">Tidak Lengkap</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
  <script>
    let datatable = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        ajax: {
          url: '{!! url()->current() !!}',
          data: function (d) {
            d.start_date = $('#start_date').val(); 
            d.end_date = $('#end_date').val(); 
            d.description = $('#description').val(); 
          }
        },
        columns: [
          {
            "data": 'DT_RowIndex',
            orderable: false, 
            searchable: false,
            className: 'text-center'
          },
          { data: 'user.nim', name: 'user.nim' },
          { data: 'user.name', name: 'user.name' },
          { data: 'user.position', name: 'user.position'},
          { data: 'user.prodi', name: 'user.prodi'},
          { data: 'date', name: 'date'},
          { data: 'entry_time', name: 'entry_time' },
          { data: 'out_time', name: 'out_time' },
          { data: 'description', name: 'description' },
          { data: 'picture', name: 'picture' },
          { 
            data: 'action', 
            name: 'action',
            orderable: false,
            searchcable: false,
            width: '15%',
            className: 'text-center'
          },
        ]
    });

    $(".filter").click(function(){
        datatable.draw();
    });
  </script>
@endpush