@extends('layouts.admin')

@section('title')
    Tambah User
@endsection

@section('container')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Tambah User
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
                    <div class="card-header">Tambah Pengguna Baru</div>
                    <div class="card-body">
                        <form id="form-tambah-user" action="{{ route('user.store') }}" method="POST" autocomplete="off">
                            @csrf

                            <!-- NIM -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="nim">NIM</label>
                                    <input class="form-control" id="nim" name="nim" type="text" placeholder="Masukan NIM.." 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="20" />
                                </div>
                            </div>

                            <!-- Nama -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="name">Nama</label>
                                    <input class="form-control" id="name" name="name" type="text" placeholder="Masukan Nama.." />
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Jenis Kelamin</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender1" value="Laki-Laki">
                                        <label class="form-check-label" for="gender1">Laki-Laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender2" value="Perempuan">
                                        <label class="form-check-label" for="gender2">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="address">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" placeholder="Masukan Alamat.."></textarea>
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="position">Jabatan</label>
                                    <input class="form-control" id="position" name="position" type="text" placeholder="Masukan Jabatan.." />
                                </div>
                            </div>

                            <!-- Program Studi (create) -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="prodi">Program Studi</label>
                                    <select class="form-select" id="prodi" name="prodi">
                                        <option value="">Pilih Program Studi</option>
                                        <option value="Akuntansi (S1)" {{ old('prodi') == 'Akuntansi (S1)' ? 'selected' : '' }}>Akuntansi (S1)</option>
                                        <option value="Manajemen (S1)" {{ old('prodi') == 'Manajemen (S1)' ? 'selected' : '' }}>Manajemen (S1)</option>
                                        <option value="Magister Manajemen (S2)" {{ old('prodi') == 'Magister Manajemen (S2)' ? 'selected' : '' }}>Magister Manajemen (S2)</option>
                                        <option value="Pengelolaan Perhotelan (D4)" {{ old('prodi') == 'Pengelolaan Perhotelan (D4)' ? 'selected' : '' }}>Pengelolaan Perhotelan (D4)</option>
                                        <option value="Pemasaran Digital (D3)" {{ old('prodi') == 'Pemasaran Digital (D3)' ? 'selected' : '' }}>Pemasaran Digital (D3)</option>
                                        <option value="Pendidikan Guru PAUD (S1)" {{ old('prodi') == 'Pendidikan Guru PAUD (S1)' ? 'selected' : '' }}>Pendidikan Guru PAUD (S1)</option>
                                        <option value="Pendidikan Kesejahteraan Keluarga (S1)" {{ old('prodi') == 'Pendidikan Kesejahteraan Keluarga (S1)' ? 'selected' : '' }}>Pendidikan Kesejahteraan Keluarga (S1)</option>
                                        <option value="Sastra Inggris (S1)" {{ old('prodi') == 'Sastra Inggris (S1)' ? 'selected' : '' }}>Sastra Inggris (S1)</option>
                                        <option value="Gizi (S1)" {{ old('prodi') == 'Gizi (S1)' ? 'selected' : '' }}>Gizi (S1)</option>
                                        <option value="Psikologi (S1)" {{ old('prodi') == 'Psikologi (S1)' ? 'selected' : '' }}>Psikologi (S1)</option>
                                        <option value="Perekam Dan Informasi Kesehatan (S1)" {{ old('prodi') == 'Perekam Dan Informasi Kesehatan (S1)' ? 'selected' : '' }}>Perekam Dan Informasi Kesehatan (S1)</option>
                                        <option value="Biologi (S1)" {{ old('prodi') == 'Biologi (S1)' ? 'selected' : '' }}>Biologi (S1)</option>
                                        <option value="Manajemen Informasi Kesehatan (D4)" {{ old('prodi') == 'Manajemen Informasi Kesehatan (D4)' ? 'selected' : '' }}>Manajemen Informasi Kesehatan (D4)</option>
                                        <option value="Kedokteran (S1)" {{ old('prodi') == 'Kedokteran (S1)' ? 'selected' : '' }}>Kedokteran (S1)</option>
                                        <option value="Profesi Dokter" {{ old('prodi') == 'Profesi Dokter' ? 'selected' : '' }}>Profesi Dokter</option>
                                        <option value="Pendidikan Profesi Fisioterapis Program Profesi" {{ old('prodi') == 'Pendidikan Profesi Fisioterapis Program Profesi' ? 'selected' : '' }}>Pendidikan Profesi Fisioterapis Program Profesi</option>
                                        <option value="Fisioterapi (S1)" {{ old('prodi') == 'Fisioterapi (S1)' ? 'selected' : '' }}>Fisioterapi (S1)</option>
                                        <option value="Kesehatan Masyarakat (S1)" {{ old('prodi') == 'Kesehatan Masyarakat (S1)' ? 'selected' : '' }}>Kesehatan Masyarakat (S1)</option>
                                        <option value="Teknik Informatika (S1)" {{ old('prodi') == 'Teknik Informatika (S1)' ? 'selected' : '' }}>Teknik Informatika (S1)</option>
                                        <option value="Sistem Informasi (S1)" {{ old('prodi') == 'Sistem Informasi (S1)' ? 'selected' : '' }}>Sistem Informasi (S1)</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Email -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="email">E-Mail</label>
                                    <input class="form-control" id="email" name="email" type="text" placeholder="Masukan E-Mail.." autocomplete="off"/>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <div class="col-md-6 position-relative">
                                    <label class="small mb-1" for="password">Password</label>
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Masukan Password.." autocomplete="new-password"/>
                                    <span class="toggle-password" onclick="togglePassword('password')" style="position:absolute; right:10px; top:35px; cursor:pointer;">👁</span>
                                </div>   
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <div class="col-md-6 position-relative">
                                    <label class="small mb-1" for="confirm_password">Ulangi Password</label>
                                    <input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="Ulangi Password.." />
                                    <span class="toggle-password" onclick="togglePassword('confirm_password')" style="position:absolute; right:10px; top:35px; cursor:pointer;">👁</span>
                                </div>   
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Role</label>
                                    <select class="form-select" id="role_id" name="role_id">
                                        <option value="">Pilih Role</option>
                                        <option value="1">Admin</option>
                                        <option value="2">User</option>
                                    </select>
                                </div> 
                            </div>

                            <button class="btn btn-primary" type="submit">Tambah Pengguna Baru</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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

<!-- SweetAlert & Password Toggle -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePassword(id) {
    let field = document.getElementById(id);
    field.type = (field.type === 'password') ? 'text' : 'password';
}

document.getElementById('form-tambah-user').addEventListener('submit', function(e) {
    e.preventDefault();

    let nim = document.getElementById('nim').value.trim();
    let name = document.getElementById('name').value.trim();
    let gender = document.querySelector('input[name="gender"]:checked');
    let address = document.getElementById('address').value.trim();
    let position = document.getElementById('position').value.trim();
    let prodi = document.getElementById('prodi').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();
    let confirm_password = document.getElementById('confirm_password').value.trim();
    let role_id = document.getElementById('role_id').value;

    if (nim === '') return Swal.fire('Oops!', 'NIM tidak boleh kosong!', 'warning');
    if (!/^\d+$/.test(nim)) return Swal.fire('Oops!', 'NIM hanya boleh berisi angka!', 'warning');
    if (name === '') return Swal.fire('Oops!', 'Nama tidak boleh kosong!', 'warning');
    if (!gender) return Swal.fire('Oops!', 'Silakan pilih jenis kelamin!', 'warning');
    if (address === '') return Swal.fire('Oops!', 'Alamat tidak boleh kosong!', 'warning');
    if (position === '') return Swal.fire('Oops!', 'Jabatan tidak boleh kosong!', 'warning');
    if (prodi === '') return Swal.fire('Oops!', 'Program Studi tidak boleh kosong!', 'warning');
    if (email === '') return Swal.fire('Oops!', 'Email tidak boleh kosong!', 'warning');
    if (!/^\S+@\S+\.\S+$/.test(email)) return Swal.fire('Oops!', 'Format email tidak valid!', 'warning');
    if (password === '') return Swal.fire('Oops!', 'Password tidak boleh kosong!', 'warning');
    if (confirm_password === '') return Swal.fire('Oops!', 'Ulangi password tidak boleh kosong!', 'warning');
    if (password !== confirm_password) return Swal.fire('Oops!', 'Password dan konfirmasi password tidak sama!', 'warning');
    if (role_id === '') return Swal.fire('Oops!', 'Silakan pilih role!', 'warning');

    this.submit();
});
</script>
@endsection
