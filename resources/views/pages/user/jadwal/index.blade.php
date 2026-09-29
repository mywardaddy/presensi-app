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
                            Daftar Kegiatan Pengembangan Karakter
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">
                        Jadwal Kegiatan
                    </div>

                    <div class="card-body">
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
    $(document).ready(function () {
        $('#jadwalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("jadwal-user.index") }}',
                type: 'GET'
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
                },
                {
                    data: 'jam',
                    render: function (data, type, row) {
                        return row.jam_mulai ? row.jam_mulai.slice(0, 5) + ' - ' + row.jam_selesai.slice(0, 5) : '-';
                    },
                    className: "text-center"
                }
            ],
            language: {
                emptyTable: "Tidak ada data jadwal yang tersedia",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)",
                loadingRecords: "Memuat...",
                processing: "Memproses...",
                search: "Cari:",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
