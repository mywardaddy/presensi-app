@extends('layouts.admin')

@section('title', 'Ubah User')

@section('container')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Ubah User
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('user.index') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali ke Semua Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">Ubah Pengguna</div>
                    <div class="card-body">

                        <form id="editUserForm" action="{{ route('user.update', $item->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="nim">NIM</label>
                                    <input class="form-control" 
                                        id="nim" 
                                        name="nim" 
                                        type="text" 
                                        placeholder="Masukan NIM.." 
                                        value="{{ old('nim', $item->nim ?? '') }}" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                        maxlength="20" />
                                </div>
                            </div>

                            <!-- Nama -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Nama</label>
                                    <input class="form-control" name="name" id="name"
                                        type="text" value="{{ old('name', $item->name) }}"
                                        placeholder="Masukan Nama.."/>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Jenis Kelamin</label> <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="Laki-Laki"
                                            {{ $item->gender == 'Laki-Laki' ? 'checked' : '' }}>
                                        <label class="form-check-label">Laki-Laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="Perempuan"
                                            {{ $item->gender == 'Perempuan' ? 'checked' : '' }}>
                                        <label class="form-check-label">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Alamat</label>
                                    <textarea name="address" id="address" class="form-control"
                                        placeholder="Masukan Alamat..">{{ old('address', $item->address) }}</textarea>
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Jabatan</label>
                                    <input class="form-control" name="position" id="position"
                                        type="text" value="{{ old('position', $item->position) }}"
                                        placeholder="Masukan Jabatan.."/>
                                </div>
                            </div>

                            <!-- Program Studi (edit) -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="prodi">Program Studi</label>
                                    <select class="form-select" id="prodi" name="prodi">
                                        <option value="">Pilih Program Studi</option>
                                        <option value="Akuntansi (S1)" {{ old('prodi', $item->prodi ?? '') == 'Akuntansi (S1)' ? 'selected' : '' }}>Akuntansi (S1)</option>
                                        <option value="Manajemen (S1)" {{ old('prodi', $item->prodi ?? '') == 'Manajemen (S1)' ? 'selected' : '' }}>Manajemen (S1)</option>
                                        <option value="Magister Manajemen (S2)" {{ old('prodi', $item->prodi ?? '') == 'Magister Manajemen (S2)' ? 'selected' : '' }}>Magister Manajemen (S2)</option>
                                        <option value="Pengelolaan Perhotelan (D4)" {{ old('prodi', $item->prodi ?? '') == 'Pengelolaan Perhotelan (D4)' ? 'selected' : '' }}>Pengelolaan Perhotelan (D4)</option>
                                        <option value="Pemasaran Digital (D3)" {{ old('prodi', $item->prodi ?? '') == 'Pemasaran Digital (D3)' ? 'selected' : '' }}>Pemasaran Digital (D3)</option>
                                        <option value="Pendidikan Guru PAUD (S1)" {{ old('prodi', $item->prodi ?? '') == 'Pendidikan Guru PAUD (S1)' ? 'selected' : '' }}>Pendidikan Guru PAUD (S1)</option>
                                        <option value="Pendidikan Kesejahteraan Keluarga (S1)" {{ old('prodi', $item->prodi ?? '') == 'Pendidikan Kesejahteraan Keluarga (S1)' ? 'selected' : '' }}>Pendidikan Kesejahteraan Keluarga (S1)</option>
                                        <option value="Sastra Inggris (S1)" {{ old('prodi', $item->prodi ?? '') == 'Sastra Inggris (S1)' ? 'selected' : '' }}>Sastra Inggris (S1)</option>
                                        <option value="Gizi (S1)" {{ old('prodi', $item->prodi ?? '') == 'Gizi (S1)' ? 'selected' : '' }}>Gizi (S1)</option>
                                        <option value="Psikologi (S1)" {{ old('prodi', $item->prodi ?? '') == 'Psikologi (S1)' ? 'selected' : '' }}>Psikologi (S1)</option>
                                        <option value="Perekam Dan Informasi Kesehatan (S1)" {{ old('prodi', $item->prodi ?? '') == 'Perekam Dan Informasi Kesehatan (S1)' ? 'selected' : '' }}>Perekam Dan Informasi Kesehatan (S1)</option>
                                        <option value="Biologi (S1)" {{ old('prodi', $item->prodi ?? '') == 'Biologi (S1)' ? 'selected' : '' }}>Biologi (S1)</option>
                                        <option value="Manajemen Informasi Kesehatan (D4)" {{ old('prodi', $item->prodi ?? '') == 'Manajemen Informasi Kesehatan (D4)' ? 'selected' : '' }}>Manajemen Informasi Kesehatan (D4)</option>
                                        <option value="Kedokteran (S1)" {{ old('prodi', $item->prodi ?? '') == 'Kedokteran (S1)' ? 'selected' : '' }}>Kedokteran (S1)</option>
                                        <option value="Profesi Dokter" {{ old('prodi', $item->prodi ?? '') == 'Profesi Dokter' ? 'selected' : '' }}>Profesi Dokter</option>
                                        <option value="Pendidikan Profesi Fisioterapis Program Profesi" {{ old('prodi', $item->prodi ?? '') == 'Pendidikan Profesi Fisioterapis Program Profesi' ? 'selected' : '' }}>Pendidikan Profesi Fisioterapis Program Profesi</option>
                                        <option value="Fisioterapi (S1)" {{ old('prodi', $item->prodi ?? '') == 'Fisioterapi (S1)' ? 'selected' : '' }}>Fisioterapi (S1)</option>
                                        <option value="Kesehatan Masyarakat (S1)" {{ old('prodi', $item->prodi ?? '') == 'Kesehatan Masyarakat (S1)' ? 'selected' : '' }}>Kesehatan Masyarakat (S1)</option>
                                        <option value="Teknik Informatika (S1)" {{ old('prodi', $item->prodi ?? '') == 'Teknik Informatika (S1)' ? 'selected' : '' }}>Teknik Informatika (S1)</option>
                                        <option value="Sistem Informasi (S1)" {{ old('prodi', $item->prodi ?? '') == 'Sistem Informasi (S1)' ? 'selected' : '' }}>Sistem Informasi (S1)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">E-Mail</label>
                                    <input class="form-control" name="email" id="email"
                                        type="email" value="{{ old('email', $item->email) }}"
                                        placeholder="Masukan E-Mail.."/>
                                </div>
                            </div>

                            <!-- Password + Toggle -->
                            <div class="mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Password</label>
                                    <div class="input-group">
                                        <input class="form-control" id="password" name="password" type="password" placeholder="Masukan Password.."/>
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Role</label>
                                    <select class="form-select" name="role_id" id="role_id">
                                        <option value="">Pilih Role</option>
                                        <option value="1" {{ $item->role_id == '1' ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ $item->role_id == '2' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Upload Foto Wajah -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Upload Foto Wajah</label>
                                    <input type="file" id="fileInput" class="form-control" name="file"/>
                                    <button type="button" class="btn btn-secondary mt-2" onclick="registerFace('{{ $item->id }}', '{{ $item->name }}')">Register Face</button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#prodi').select2({
        placeholder: "Pilih atau ketik Program Studi",
        allowClear: true
    });
});
</script>


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    pwd.type = (pwd.type === "password") ? "text" : "password";
}

document.getElementById("editUserForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let nim = document.getElementById("nim").value.trim();
    let name = document.getElementById("name").value.trim();
    let gender = document.querySelector('input[name="gender"]:checked');
    let address = document.getElementById('address').value.trim();
    let position = document.getElementById('position').value.trim();
    let prodi = document.getElementById('prodi').value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let role = document.getElementById("role_id").value;

    if (!nim || !/^[0-9]+$/.test(nim)) {
        Swal.fire("Peringatan", "NIM wajib diisi dan hanya boleh angka", "warning");
        return;
    }
    if (!name) {
        Swal.fire("Peringatan", "Nama wajib diisi", "warning");
        return;
    }
    if (!gender) {
        Swal.fire("Peringatan", "Jenis kelamin wajib dipilih", "warning");
        return;
    }
    if (!address) {
        Swal.fire("Peringatan", "Alamat wajib diisi", "warning");
        return;
    }
    if (!position) {
        Swal.fire("Peringatan", "Jabatan wajib diisi", "warning");
        return;
    }
    if (!prodi) {
        Swal.fire("Peringatan", "Program studi wajib diisi", "warning");
        return;
    }
    if (!email) {
        Swal.fire("Peringatan", "Email wajib diisi", "warning");
        return;
    }
    if (password && password.length < 6) {
        Swal.fire("Peringatan", "Password minimal 6 karakter", "warning");
        return;
    }
    if (!role) {
        Swal.fire("Peringatan", "Silakan pilih role", "warning");
        return;
    }

    // Submit form kalau semua validasi lolos
    this.submit();
});

async function registerFace(id, name) {
    const fileInput = document.getElementById("fileInput");
    if (!fileInput.files.length) {
        Swal.fire("Peringatan", "Silakan pilih file terlebih dahulu.", "warning");
        return;
    }

    const formData = new FormData();
    formData.append("id", id);
    formData.append("name", name);
    formData.append("file", fileInput.files[0]);

    try {
        const response = await fetch("http://127.0.0.1:5050/register_face", {
            method: "POST",
            body: formData
        });

        const data = await response.json(); // simpan hasil JSON
        console.log("Response dari backend:", data);

        if (response.ok && data.message && data.message.includes("Berhasil")) {
    Swal.fire("Berhasil", data.message, "success").then(() => {
        window.location.reload();
    });
} else {
    Swal.fire("Gagal", data.message || "Terjadi kesalahan saat registrasi wajah.", "error");
}
    } catch (error) {
        Swal.fire("Gagal", "Gagal mengunggah wajah, coba lagi nanti.", "error");
    }
}
</script>
</main>
@endsection
