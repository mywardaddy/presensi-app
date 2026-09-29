@extends('layouts.admin')

@section('title')
    Jadwal 7 Karakter
@endsection

@section('container')
<main>
    {{-- Header --}}
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="calendar"></i>
                            </div>
                            Jadwal 7 Karakter
                        </h1>
                        <div class="page-header-subtitle">
                            List Jadwal Kegiatan 7 Karakter
                        </div>
                    </div>
                </div>
                <nav class="mt-4 rounded" aria-label="breadcrumb">
                    <ol class="breadcrumb px-3 py-2 rounded mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jadwal 7 Karakter</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">

                    {{-- Tombol Tambah --}}
                    <div class="card-header">
                        <a class="btn btn-sm btn-primary" href="{{ route('jadwal.create') }}">
                            <i class="fas fa-plus me-1"></i> Tambah Jadwal Baru
                        </a>
                    </div>

                    {{-- Alert Message --}}
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show hide-alert" role="alert">
                                {{ session('success') }}
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

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

                        {{-- Tabel --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm" id="jadwalTable">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" width="10">No.</th>
                                        <th class="text-center align-middle">Kode Karakter</th>
                                        <th class="text-left align-middle">Nama Jadwal</th>
                                        <th class="text-left align-middle">Deskripsi</th>
                                        <th class="text-left align-middle">Opening</th>
                                        <th class="text-left align-middle">Narasumber</th>
                                        <th class="text-left align-middle">Moderator</th>
                                        <th class="text-center align-middle">Tanggal</th>
                                        <th class="text-center align-middle">Jam</th>
                                        <th class="text-center align-middle">Aksi</th>
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
    {{-- Library eksternal --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Inisialisasi DataTable
            $('#jadwalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("jadwal.index") }}',
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.error('DataTables AJAX error:', xhr, error, thrown);
                    }
                },
                dom: '<"top"i>rt<"bottom"lp><"clear">',
                searching: false,
                lengthChange: false,
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
                    { data: 'kode_karakter', className: "text-center" },
                    { data: 'nama_jadwal' },
                    { data: 'deskripsi' },
                    { data: 'opening' },
                    { data: 'narasumber' },
                    { data: 'moderator' },
                    {
                        data: 'tanggal',
                        className: "text-center"
                        // Tanggal sudah diformat string dari server, jadi tampilkan apa adanya
                    },
                    {
                        data: 'jam',
                        render: function (data, type, row) {
                            const formatJam = jam => jam ? jam.slice(0, 5) : '';
                            return formatJam(row.jam_mulai) + ' - ' + formatJam(row.jam_selesai);
                        },
                        className: "text-center"
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                ]
            });

            // SweetAlert konfirmasi hapus
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                $('#jadwalTable').DataTable().ajax.reload();
                                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                            },
                            error: function (xhr) {
                                console.error(xhr.responseText);
                                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
                            }
                        });
                    }
                });
            });

            // Auto-hide alert
            window.setTimeout(() => {
                $(".hide-alert").fadeTo(500, 0).slideUp(300, function () {
                    $(this).remove();
                });
            }, 4000);
        });
    </script>
@endpush
