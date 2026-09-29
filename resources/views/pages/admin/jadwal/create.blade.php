@extends('layouts.app')

@section('title', 'Tambah Jadwal 7 Karakter')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
    }
    .card-modern {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.75);
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern p-4">
                <h3 class="mb-4 text-center text-success">Tambah Jadwal 7 Karakter</h3>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('jadwal.store') }}" method="POST" id="createForm">
                    @csrf

                    <div class="mb-3">
                        <label for="kode_karakter" class="form-label fw-semibold">Kode Karakter (7 karakter)</label>
                        <input type="text" name="kode_karakter" class="form-control" maxlength="100"
                            placeholder="Contoh: Webinar Lokal, Seminar Nasional"
                            value="{{ old('kode_karakter') }}">
                    </div>
                    <div class="mb-3">
                        <label for="nama_jadwal" class="form-label fw-semibold">Nama Jadwal</label>
                        <input type="text" name="nama_jadwal" class="form-control"
                            placeholder="Contoh: Pembelajaran 7 Karakter Bagi Mahasiswa UNDHIRA"
                            value="{{ old('nama_jadwal') }}">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                            placeholder="Contoh: 7 Karakter Menjadi Pedoman UNDHIRA Khususnya Pada Mahasiswa Undhira...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="opening" class="form-label fw-semibold">Opening</label>
                        <input type="text" class="form-control" name="opening" id="opening"
                            placeholder="Contoh: Prof. Dr. I Gusti Bagus Rai Utama, SE, M.MA, MA"
                            value="{{ old('opening') }}">
                    </div>
                    <div class="mb-3">
                        <label for="narasumber" class="form-label fw-semibold">Narasumber</label>
                        <input type="text" class="form-control" name="narasumber" id="narasumber"
                            placeholder="Contoh: Dr. Christimulia Purnama  Trimurti, S.E., S.H., M.M"
                            value="{{ old('narasumber') }}">
                    </div>
                    <div class="mb-3">
                        <label for="moderator" class="form-label fw-semibold">Moderator</label>
                        <input type="text" class="form-control" name="moderator" id="moderator"
                            placeholder="Contoh: Ni Putu Dyah Krismawintari, S.E., M.M."
                            value="{{ old('moderator') }}">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control"
                            value="{{ old('tanggal') }}">
                    </div>
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label fw-semibold">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control"
                            value="{{ old('jam_mulai') }}">
                    </div>
                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label fw-semibold">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control"
                            value="{{ old('jam_selesai') }}">
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_shared" name="is_shared"
                            {{ old('is_shared', $jadwal->is_shared ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_shared">
                            Bagikan Jadwal ke Mahasiswa
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success px-4 shadow">Simpan</button>
                        <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('createForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let kode        = document.querySelector('[name="kode_karakter"]').value.trim();
    let nama        = document.querySelector('[name="nama_jadwal"]').value.trim();
    let deskripsi   = document.querySelector('[name="deskripsi"]').value.trim();
    let opening     = document.querySelector('[name="opening"]').value.trim();
    let narasumber  = document.querySelector('[name="narasumber"]').value.trim();
    let moderator   = document.querySelector('[name="moderator"]').value.trim();
    let tanggal     = document.querySelector('[name="tanggal"]').value.trim();
    let jamMulai    = document.querySelector('[name="jam_mulai"]').value.trim();
    let jamSelesai  = document.querySelector('[name="jam_selesai"]').value.trim();

    if (!kode || !nama || !deskripsi || !opening || !narasumber || !moderator || !tanggal || !jamMulai || !jamSelesai) {
        Swal.fire({
            icon: 'warning',
            title: 'Form Belum Lengkap',
            text: 'Harap isi semua field sebelum menyimpan.',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    if (kode.length !== 7) {
        Swal.fire({
            icon: 'warning',
            title: 'Kode Karakter Salah',
            text: 'Kode Karakter harus tepat 7 karakter!',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    Swal.fire({
        title: 'Simpan Jadwal?',
        text: "Pastikan semua data sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif
@endsection
