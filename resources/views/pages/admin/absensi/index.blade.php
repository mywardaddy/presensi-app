@extends('layouts.admin')

@section('title')
    Absensi
@endsection

@section('container')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="clock"></i></div>
                                Absensi
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
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Tambah Absensi</div>
                        <div class="card-body">
                            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="name">Nama</label>
                                        <input class="form-control" name="name" type="text" readonly/>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="jk">Jenis Kelamin</label>
                                        <input class="form-control" name="jk" type="text" readonly/>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="jabatan">Jabatan</label>
                                        <input class="form-control" name="jabatan" type="text" readonly/>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="prodi">Program Studi</label>
                                        <input class="form-control" name="prodi" type="text" readonly/>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="jk">Lattitude</label>
                                        <input class="form-control" name="jk" type="text" readonly/>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="jk">Longitude</label>
                                        <input class="form-control" name="jk" type="text" readonly/>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="jk">Keterangan</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Pilih...</option>
                                            <option value="Hadir">Hadir</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="">Foto</label> <br>
                                        <input type="file" id="profile" name="profile" style="display: none;" accept="image/*" capture="environment"/> 
                                        <button class="btn btn-success" type="button" onclick="thisFileUpload();">
                                        <i data-feather="camera"></i> &nbsp; Ambil Foto
                                        </button>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="jk"></label>
                                        <img id="preview-img" src="/admin/assets/img/empty-image.jpg" class="img-fluid rounded mb-2" alt="Preview Image" style="height: 165px;">
                                    </div>
                                </div>

                                <!-- Submit button-->
                                <button class="btn btn-primary" type="submit">
                                    Simpan Absen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('addon-script')
    <script>
        function thisFileUpload() {
            document.getElementById("profile").click();
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); 
            }
        }

        $("#profile").change(function() {
            readURL(this);
        });
    </script> 
@endpush





