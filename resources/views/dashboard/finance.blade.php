@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-gradient-success text-white rounded p-3 align-items-center shadow-sm" style="background: linear-gradient(90deg, #28a745 0%, #20c997 100%);">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-money mr-2"></i>Dashboard Staf Finance</h4>
                <p class="mb-0 text-white-50">Verifikasi Keuangan & Pembayaran Mahasiswa</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <div class="d-flex align-items-center">
                <span class="mr-3">Role Aktif:</span>
                <span class="badge badge-light text-success border-0 px-3 py-2" style="font-size: 14px;">Staf Finance</span>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden bg-primary">
                <div class="card-body p-4 text-center">
                    <span class="mr-3 mb-2 d-inline-block">
                        <i class="la la-money text-white" style="font-size: 50px; opacity: 0.9;"></i>
                    </span>
                    <div class="mb-2">
                        <h4 class="text-white">Menunggu Verifikasi</h4>
                        <p class="text-white-50 mb-0">Pembayaran SPP/Skripsi</p>
                    </div>
                    <h2 class="text-white font-weight-bold mb-0">24</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden bg-success">
                <div class="card-body p-4 text-center">
                    <span class="mr-3 mb-2 d-inline-block">
                        <i class="la la-check-circle text-white" style="font-size: 50px; opacity: 0.9;"></i>
                    </span>
                    <div class="mb-2">
                        <h4 class="text-white">Telah Diverifikasi</h4>
                        <p class="text-white-50 mb-0">Bulan Ini (Juli 2026)</p>
                    </div>
                    <h2 class="text-white font-weight-bold mb-0">142</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-12">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden bg-info">
                <div class="card-body p-4 text-center">
                    <span class="mr-3 mb-2 d-inline-block">
                        <i class="la la-file-invoice text-white" style="font-size: 50px; opacity: 0.9;"></i>
                    </span>
                    <div class="mb-2">
                        <h4 class="text-white">Tagihan Belum Lunas</h4>
                        <p class="text-white-50 mb-0">Mahasiswa Tingkat Akhir</p>
                    </div>
                    <h2 class="text-white font-weight-bold mb-0">58</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Antrean Verifikasi Keuangan -->
        <div class="col-xl-12 col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Antrean Verifikasi Keuangan (Syarat Ujian/Yudisium)</h4>
                    <div class="d-flex">
                        <select class="form-control form-control-sm mr-2" style="width: 150px;">
                            <option value="">Semua Jenis</option>
                            <option value="SPP">SPP Semester</option>
                            <option value="Bimbingan">Biaya Bimbingan</option>
                            <option value="Sidang">Biaya Sidang</option>
                        </select>
                        <button class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Nominal</th>
                                    <th>Bukti Transaksi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>{{ \Carbon\Carbon::now()->subHours($i * 2)->format('d M Y, H:i') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=MHS+{{ $i }}&background=random" class="rounded-circle mr-2" width="30" alt="">
                                            Mahasiswa Keuangan {{ $i }}
                                        </div>
                                    </td>
                                    <td>191052{{ 100 + $i }}</td>
                                    <td><span class="badge badge-outline-primary">Biaya Sidang Skripsi</span></td>
                                    <td>Rp 500.000,-</td>
                                    <td>
                                        <a href="javascript:void(0)" class="text-info"><i class="fa fa-file-image-o mr-1"></i> bukti_tf_{{ $i }}.jpg</a>
                                    </td>
                                    <td><span class="badge badge-light text-warning"><i class="fa fa-clock-o mr-1"></i> Menunggu Verifikasi</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success mr-1" title="Approve"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Reject"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="javascript:void(0)" class="text-primary">Lihat Semua Antrean (24) <i class="fa fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
