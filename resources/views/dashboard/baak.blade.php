@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-gradient-info text-white rounded p-3 align-items-center shadow-sm" style="background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-university mr-2"></i>Dashboard Staf BAAK</h4>
                <p class="mb-0 text-white-50">Sistem Manajemen Skripsi & Yudisium</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <div class="d-flex align-items-center">
                <span class="mr-3">Role Aktif:</span>
                <span class="badge badge-light text-info border-0 px-3 py-2" style="font-size: 14px;">Staf BAAK</span>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-primary text-white">
                        <div class="align-self-center mr-auto">
                            <h4 class="text-white mb-1">Total Mahasiswa</h4>
                            <h2 class="text-white font-weight-bold mb-0">1,245</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-users" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-warning text-white">
                        <div class="align-self-center mr-auto">
                            <h4 class="text-white mb-1">Antrean Validasi BAAK</h4>
                            <h2 class="text-white font-weight-bold mb-0">12</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-file-alt" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-info text-white">
                        <div class="align-self-center mr-auto">
                            <h4 class="text-white mb-1">Skripsi Aktif</h4>
                            <h2 class="text-white font-weight-bold mb-0">84</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-book" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-success text-white">
                        <div class="align-self-center mr-auto">
                            <h4 class="text-white mb-1">Pendaftar Yudisium</h4>
                            <h2 class="text-white font-weight-bold mb-0">28</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-graduation-cap" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Aksi Cepat</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="javascript:void(0)" class="btn btn-outline-primary mr-2 mb-2"><i class="la la-check-square mr-1"></i> Validasi Nilai & Akademik</a>
                        <a href="{{ route('yudisiums.index') }}" class="btn btn-outline-success mr-2 mb-2"><i class="la la-graduation-cap mr-1"></i> Kelola Periode Yudisium</a>
                        <a href="javascript:void(0)" class="btn btn-outline-info mr-2 mb-2"><i class="la la-user-plus mr-1"></i> Tambah Data Mahasiswa</a>
                        <a href="javascript:void(0)" class="btn btn-outline-warning mr-2 mb-2"><i class="la la-calendar mr-1"></i> Jadwal Sidang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Antrean Validasi Akademik (BAAK) -->
        <div class="col-xl-8 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Antrean Validasi Akademik (Syarat Yudisium)</h4>
                    <span class="badge badge-warning text-white">12 Pending</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Program Studi</th>
                                    <th>Status Dokumen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td><strong>191051{{ 100 + $i }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Mahasiswa+{{ $i }}&background=random" class="rounded-circle mr-2" width="30" alt="">
                                            Mahasiswa Teknik {{ $i }}
                                        </div>
                                    </td>
                                    <td>Teknik Informatika</td>
                                    <td><span class="badge badge-light text-warning"><i class="fa fa-clock-o mr-1"></i> Menunggu Validasi</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Review</button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periode Yudisium Aktif -->
        <div class="col-xl-4 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0">
                    <h4 class="card-title mb-0">Jadwal Yudisium Aktif</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0 shadow-sm">
                        <h4 class="alert-heading font-weight-bold text-success mb-2">Periode Ganjil 2026/2027</h4>
                        <p class="mb-1"><i class="fa fa-calendar-alt mr-2"></i> Pendaftaran: 10 Ags - 25 Ags 2026</p>
                        <p class="mb-2"><i class="fa fa-clock mr-2"></i> Pelaksanaan: 10 Sep 2026</p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Peserta: <strong>154 Mahasiswa</strong></span>
                            <a href="{{ route('yudisiums.index') }}" class="btn btn-sm btn-success text-white">Detail</a>
                        </div>
                    </div>

                    <div class="alert alert-secondary border-0 mt-3">
                        <p class="mb-0 text-muted text-center"><i class="fa fa-info-circle mr-1"></i> Tidak ada periode yudisium lain yang terbuka saat ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
