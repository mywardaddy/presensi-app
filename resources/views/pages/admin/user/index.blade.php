@extends('layouts.admin')

@section('title')
    Data User
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
                                    <i data-feather="users"></i>
                                </div>
                                Data Admin & User
                            </h1>
                            <div class="page-header-subtitle">List Admin & User</div>
                        </div>
                    </div>
                    <nav class="mt-4 rounded" aria-label="breadcrumb">
                        <ol class="breadcrumb px-3 py-2 rounded mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Data User</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
    <a class="btn btn-sm btn-primary" href="{{ route('user.create') }}">
        Tambah User Baru
    </a>

    <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="d-flex">
        @csrf
        <input type="file" name="file" class="form-control form-control-sm me-2" required>
        <button class="btn btn-sm btn-success" type="submit">
            Import Excel
        </button>
    </form>
</div>
                        <div class="card-body">
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                            {{-- List Data --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" id="userTable">
                                    <thead>
                                        <tr>
                                            <th width="10" style="vertical-align: middle;" class="text-center">No.</th>
                                            <th style="vertical-align: middle;" class="text-left">NIM</th>
                                            <th style="vertical-align: middle;" class="text-left">Nama</th>
                                            <th style="vertical-align: middle;" class="text-left">JK</th>
                                            <th style="vertical-align: middle;" class="text-left">Jabatan</th>
                                            <th style="vertical-align: middle;" class="text-left">Program Studi</th>
                                            <th style="vertical-align: middle;" class="text-left">Role</th>
                                            <th style="vertical-align: middle;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </main>
@endsection

@push('addon-script')
  <script>
    $(document).ready(function() {
    $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {            
            url: '{{ route("user.index") }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTables AJAX error:', xhr, error, thrown);
        }},
        columns: [
        {
            "data": 'DT_RowIndex',
            orderable: false, 
            searchable: false,
            "className": "text-center",
        },
        { data: 'nim', name: 'nim' },
        { data: 'name', name: 'name' },
        { data: 'gender', name: 'gender' },
        { data: 'position', name: 'position' },
        { data: 'prodi', name: 'prodi' },
        { data: 'role.name', name: 'role.name' },
        { 
            data: 'action', 
            name: 'action',
            orderable: false,
            searcable: false,
            width: '15%',
            "className": "text-center",
        },
                ]
    });
    });


    // $.ajax({
    // url: "{{ route('user.index') }}",
    // method: "GET",
    // success: function(response) {
    //     console.log(response);
    // }
    // });  
</script>

@endpush

