@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-gradient-warning text-white rounded p-3 align-items-center shadow-sm" style="background: linear-gradient(90deg, #fd7e14 0%, #ffc107 100%);">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-book-reader mr-2"></i>Dashboard Staf Perpustakaan</h4>
                <p class="mb-0 text-white-50">Verifikasi Bebas Pustaka & Penyerahan Jurnal</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <div class="d-flex align-items-center">
                <span class="mr-3 text-white">Role Aktif:</span>
                <span class="badge badge-light text-warning border-0 px-3 py-2" style="font-size: 14px;">Staf Perpustakaan</span>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden bg-primary">
                <div class="card-body p-4 text-center">
                    <span class="mr-3 mb-2 d-inline-block">
                        <i class="la la-exclamation-circle text-white" style="font-size: 50px; opacity: 0.9;"></i>
                    </span>
                    <div class="mb-2">
                        <h4 class="text-white">Menunggu Bebas Pustaka</h4>
                        <p class="text-white-50 mb-0">Permohonan Clearance</p>
                    </div>
                    <h2 class="text-white font-weight-bold mb-0">15</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden bg-info">
                <div class="card-body p-4 text-center">
                    <span class="mr-3 mb-2 d-inline-block">
                        <i class="la la-file-upload text-white" style="font-size: 50px; opacity: 0.9;"></i>
                    </span>
                    <div class="mb-2">
                        <h4 class="text-white">Menunggu Review Jurnal</h4>
                        <p class="text-white-50 mb-0">Penyerahan Skripsi/Jurnal</p>
                    </div>
                    <h2 class="text-white font-weight-bold mb-0">8</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-12">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden bg-success">
                <div class="card-body p-4 text-center">
                    <span class="mr-3 mb-2 d-inline-block">
                        <i class="la la-check-circle text-white" style="font-size: 50px; opacity: 0.9;"></i>
                    </span>
                    <div class="mb-2">
                        <h4 class="text-white">Clearance Selesai</h4>
                        <p class="text-white-50 mb-0">Bulan Ini (Juli 2026)</p>
                    </div>
                    <h2 class="text-white font-weight-bold mb-0">42</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Antrean Verifikasi Perpustakaan -->
        <div class="col-xl-12 col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Antrean Verifikasi Perpustakaan</h4>
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active btn-sm py-1" id="pills-clearance-tab" data-toggle="pill" href="#pills-clearance" role="tab">Bebas Pustaka (15)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-sm py-1" id="pills-jurnal-tab" data-toggle="pill" href="#pills-jurnal" role="tab">Penyerahan Jurnal/Skripsi (8)</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <!-- Tab Bebas Pustaka -->
                        <div class="tab-pane fade show active" id="pills-clearance" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tgl Permohonan</th>
                                            <th>Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Status Pinjaman Buku</th>
                                            <th>Status Denda</th>
                                            <th>Keputusan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= 5; $i++)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::now()->subDays($i)->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name=MHS+{{ $i }}&background=random" class="rounded-circle mr-2" width="30" alt="">
                                                    Mahasiswa Perpus {{ $i }}
                                                </div>
                                            </td>
                                            <td>191053{{ 100 + $i }}</td>
                                            <td>
                                                @if($i % 3 == 0)
                                                    <span class="text-danger"><i class="fa fa-times-circle mr-1"></i> 2 Buku Belum Kembali</span>
                                                @else
                                                    <span class="text-success"><i class="fa fa-check-circle mr-1"></i> Tidak Ada Pinjaman</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($i % 3 == 0)
                                                    <span class="text-danger">Rp 15.000,-</span>
                                                @else
                                                    <span class="text-success">Lunas</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success mr-1" title="Approve Clearance" {{ $i % 3 == 0 ? 'disabled' : '' }}><i class="fa fa-check"></i> Bebas Pustaka</button>
                                                @if($i % 3 == 0)
                                                    <button class="btn btn-sm btn-danger" title="Tolak / Kirim Peringatan"><i class="fa fa-envelope"></i> Ingatkan</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Tab Penyerahan Jurnal -->
                        <div class="tab-pane fade" id="pills-jurnal" role="tabpanel">
                            <div class="text-center p-5 text-muted">
                                <i class="fa fa-file-pdf-o fa-3x mb-3"></i>
                                <h5>Menunggu Penyerahan Dokumen Final Skripsi & Jurnal</h5>
                                <p>Silakan gunakan menu 'Pengumpulan Skripsi' untuk mengelola ini secara detail.</p>
                                <button class="btn btn-primary mt-2">Buka Halaman Pengumpulan Skripsi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
