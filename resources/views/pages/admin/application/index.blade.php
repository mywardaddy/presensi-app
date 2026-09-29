@extends('layouts.admin')

@section('title')
    Setup Aplikasi
@endsection

@section('container')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="settings"></i></div>
                                Pengaturan - Setup Aplikasi
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                <a class="nav-link {{ (request()->is('admin/setting')) ? 'active ms-0' : '' }}" href="{{ route('setting.index') }}">Profil</a>
                <a class="nav-link {{ (request()->is('admin/setting/change-password')) ? 'active ms-0' : '' }}" href="{{ route('change-password') }}">Ubah Password</a>
                <a class="nav-link {{ (request()->is('admin/setting/application')) ? 'active ms-0' : '' }}" href="{{ route('change-application') }}">Setup Aplikasi</a>
            </nav>
            <hr class="mt-0 mb-4" />
            <div class="row">
                <div class="col">
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
                </div>
            </div>
            <div class="row">
                <div class="col-xl-5">
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Logo Website</div>
                        <div class="card-body text-center">
                            <!-- Profile picture image-->
                            @if ($app->logo != NULL)
                                <img class="img-account-profile mb-2" src="{{ asset('logo/' . $app->logo) }}" alt="Logo {{ $app->name ?? 'Aplikasi' }}" style="height: 75px;"/>
                            @else
                                <img 
                                    class="img-account-profile mb-2" 
                                    src="https://placehold.co/200x100/f0f0f0/666?text=LOGO+APP&font=arial" 
                                    alt="Tidak ada logo"
                                    style="height: 75px;">
                            @endif       
                        <div class="mb-3"></div>
                            <!-- Profile picture upload button-->
                            <form action="{{ route('logo-upload') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $app->id }}">
                                <input
                                type="file"
                                id="logo"
                                name="logo"
                                style="display: none;"
                                accept="image/*"
                                onchange="form.submit()"
                                />    
                                <button class="btn btn-primary btn-sm" type="button" onclick="thisFileUpload();">
                                    <i data-feather="upload"></i> &nbsp; Unggah
                                </button>
                                @if ($app->logo != NULL)
                                    <a href="{{ route('logo-delete', $app->id) }}" class="btn btn-danger btn-sm" id="logo-delete">
                                        <i data-feather="trash"></i> &nbsp; Hapus
                                    </a>  
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Wallpaper Login card -->
<div class="card mb-4 mb-xl-0 mt-3">
    <div class="card-header">Wallpaper Login</div>
    <div class="card-body text-center">
        <!-- Wallpaper Picture -->
        @if ($app->wallpaper != NULL)
            <img class="img-account-profile mb-2" 
                src="{{ asset('wallpapers/' . $app->wallpaper) }}" 
                alt="Wallpaper {{ $app->name ?? 'Aplikasi' }}" 
                style="max-width: 100%; max-height: 300px; object-fit: contain;" />
        @else
            <img 
                class="img-account-profile mb-2" 
                src="https://placehold.co/600x300/f0f0f0/666?text=WALLPAPER+APP&font=arial" 
                alt="Tidak ada wallpaper"
                style="max-width: 100%; max-height: 300px;" />
        @endif       

        <div class="mb-3"></div>

        <!-- Wallpaper Login upload button -->
        <form action="{{ route('wallpaper-upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $app->id }}">
            <input
                type="file"
                id="wallpaper"
                name="wallpaper"
                style="display: none;"
                accept="image/*"
                onchange="this.form.submit()" 
            />    
            <button class="btn btn-primary btn-sm" type="button" onclick="document.getElementById('wallpaper').click();">
                <i data-feather="upload"></i> &nbsp; Unggah
            </button>

            @if ($app->wallpaper != NULL)
                <a href="{{ route('wallpaper-delete', $app->id) }}" class="btn btn-danger btn-sm" id="wallpaper-delete">
                    <i data-feather="trash"></i> &nbsp; Hapus
                </a>  
            @endif
        </form>
    </div>
</div>


                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0 mt-3">
                        <div class="card-header">Favicon</div>
                        <div class="card-body text-center">
                            <!-- Profile picture image-->
                            @if ($app && $app->favicon != NULL)
                                <img class="img-account-profile mb-2" src="{{ asset('favicon/' . $app->favicon) }}" alt="Logo Favicon" style="height: 75px;" />
                            @else
                                <img 
                                    class="img-account-profile mb-2" 
                                    src="https://placehold.co/200x100/f0f0f0/666?text=LOGO+APP&font=arial" 
                                    alt="Tidak ada logo"
                                    style="height: 75px;">
                            @endif

                            <div class="mb-3"></div>
                            <!-- Profile picture upload button-->
                            <form action="{{ route('favicon-upload') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $app->id }}">
                                <input
                                type="file"
                                id="favicon"
                                name="favicon"
                                style="display: none;"
                                accept="image/*"
                                onchange="form.submit()"
                                />    
                                <button class="btn btn-primary btn-sm" type="button" onclick="thisFaviconUpload();">
                                    <i data-feather="upload"></i> &nbsp; Unggah
                                </button>
                                @if ($app->favicon != NULL)
                                    <a href="{{ route('favicon-delete', $app->id) }}" class="btn btn-danger btn-sm" id="favicon-delete">
                                        <i data-feather="trash"></i> &nbsp; Hapus
                                    </a>  
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Informasi Aplikasi</div>
                        <div class="card-body">
                            {{-- Alert --}}
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show hide-alert" role="alert">
                                    {{ session('success') }}
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <form action="{{ route('update.application', $app->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="small mb-1" for="app_name">Nama Aplikasi</label>
                                    <input class="form-control" name="app_name" id="app_name" type="text" placeholder="Masukan Nama Aplikasi.." value="{{ $app->app_name }}" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="is_photo">Absensi dengan Foto</label>
                                    <select name="is_photo" id="is_photo" class="form-control" required>
                                        <option value="">Pilih..</option>
                                        <option value="1" {{ ($app->is_photo == '1')?'selected':'' }}>Aktif</option>
                                        <option value="0" {{ ($app->is_photo == '0')?'selected':'' }}>Non Aktif</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="is_active">Status Absensi</label>
                                    <select name="is_active" id="is_active" class="form-control" required>
                                        <option value="">Pilih..</option>
                                        <option value="1" {{ ($app->is_active == '1')?'selected':'' }}>Aktif</option>
                                        <option value="0" {{ ($app->is_active == '0')?'selected':'' }}>Non Aktif</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="copyright">Hak Cipta</label>
                                    <input class="form-control" name="copyright" id="copyright" type="text" placeholder="Masukan Nama Hak Cipta.." value="{{ $app->copyright }}" required/>
                                </div>
                                <!-- Save changes button-->
                                <button class="btn btn-primary" type="submit">Perbarui Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const thisFileUpload = () => {
            document.getElementById("logo").click();
        }

        const thisFaviconUpload = () => {
            document.getElementById("favicon").click();
        }

        $('#logo-delete').click(function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus secara permanen !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value) {
                    document.location.href = href;
                }
            })
        });

        $('#favicon-delete').click(function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus secara permanen !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value) {
                    document.location.href = href;
                }
            })
        });

        window.setTimeout(() => {
            $(".hide-alert").fadeTo(500, 0).slideUp(300, () => {
                $(this).remove(); 
            });
        }, 4000);
    </script> 
@endpush

