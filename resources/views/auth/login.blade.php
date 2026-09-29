@php
    use App\Models\Application;
    $app = Application::first();
@endphp

@extends('layouts.auth')

@section('main')
<main class="d-flex align-items-center min-vh-100" 
    style="background: url('{{ $app && $app->wallpaper ? asset('wallpapers/' . $app->wallpaper) : asset('gambardefault/undhiraa.jpg') }}') no-repeat center center/cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card glass-effect border-0 shadow-lg rounded-4">
                    <div class="card-header text-center bg-transparent border-0">
                        <div class="img-container mt-3 mb-2 text-center">
                            @if ($app && $app->logo)
                                <img src="{{ asset('logo/' . $app->logo) }}" alt="Logo" class="rounded-logo">
                            @else
                                <img src="https://placehold.co/200x100/f0f0f0/666?text=LOGO+APP&font=arial" alt="Tidak ada logo" class="rounded-logo">
                            @endif
                        </div>
                    </div>

                    <div class="card-body px-4 py-4">
                        <form id="loginForm" action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label text-white">Email</label>
                                <input type="email" name="email" id="email" 
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" autofocus placeholder="Masukkan Email Anda">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password dengan toggle -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label text-white">Password</label>
                                <input type="password" name="password" id="password" 
                                    class="form-control pe-5" placeholder="Masukkan Password Anda">
                                <span id="togglePassword" class="toggle-password">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="rememberPasswordCheck" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="rememberPasswordCheck">Ingat Saya</label>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary fw-semibold">MASUK</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer bg-transparent border-0 text-center">
                        <small class="text-white-50">© {{ date('Y') }} Universitas Dhyana Pura</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('addon-style')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    .glass-effect {
        backdrop-filter: blur(15px);
        background-color: rgba(22, 41, 103, 0.35);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        border-radius: 10px;
        color: #ffffff;
    }
    .form-control {
        background-color: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.75);
    }
    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.25);
        border-color: #6c63ff;
        color: #ffffff;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
    }
    .rounded-logo {
        width: 135px;
        height: 135px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(0, 0, 0, 0.33);
    }
    .toggle-password {
    position: absolute;
    top: 55%;            /* posisi di tengah vertikal */
    right: 15px;         /* jarak dari kanan */
    transform: translateY(0%); /* bener-bener center */
    cursor: pointer;
    color: rgba(0, 0, 0, 0.8); /* warna ikon */
}
</style>
@endpush

@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // Validasi form kosong
    form.addEventListener('submit', function (e) {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (email === '' && password === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Form Kosong',
                text: 'Email dan Password tidak boleh kosong!',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        if (email === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Email Kosong',
                text: 'Silakan masukkan email Anda!',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        if (password === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password Kosong',
                text: 'Silakan masukkan password Anda!',
                confirmButtonColor: '#f39c12'
            });
            return;
        }
    });

    // Toggle password visibility
    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // ubah ikon mata
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
});
</script>

@if (session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
    });
});
</script>
@endif

@if (session('loginError'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'error',
        title: 'Gagal Login',
        text: '{{ session('loginError') }}',
        confirmButtonColor: '#d33'
    });
});
</script>
@endif
@endpush
