@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row mx-0 mb-4 bg-dark text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-chalkboard-teacher mr-2 text-white"></i>Dashboard Dosen</h4>
                <p class="mb-0 text-white-50">Monitoring Mahasiswa Bimbingan & Jadwal Ujian</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <div class="d-flex align-items-center">
                <span class="mr-3">Role Aktif:</span>
                <span class="badge badge-light text-dark border-0 px-3 py-2" style="font-size: 14px;">Dosen Pembimbing/Penguji</span>
            </div>
        </div>
    </div>

    <!-- Dosen Selector (For Testing/Preview) -->
    @if(auth()->user()->role !== 'lecturer' && $lecturers->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-3 d-flex align-items-center">
                    <span class="mr-3 font-weight-bold text-dark"><i class="fa fa-eye mr-1"></i> Preview Dashboard Sebagai:</span>
                    <form action="{{ route('dashboard.dosen') }}" method="GET" class="d-flex flex-grow-1 mr-3">
                        <select name="lecturer_id" class="form-control form-control-sm mr-2 w-100" onchange="this.form.submit()">
                            @foreach($lecturers as $lecturerItem)
                                <option value="{{ $lecturerItem->id }}" {{ isset($lecturer) && $lecturer->id == $lecturerItem->id ? 'selected' : '' }}>
                                    {{ $lecturerItem->user?->name }} (NIDN: {{ $lecturerItem->nidn }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @if(isset($lecturer))
                        <span class="badge badge-primary px-3 py-2">Dosen Aktif: {{ $lecturer->user?->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif



    @if(isset($lecturer))
    <!-- Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-primary text-white">
                        <div class="align-self-center mr-auto">
                            <h4 class="text-white mb-1">Mahasiswa Bimbingan</h4>
                            <h2 class="text-white font-weight-bold mb-0">8</h2>
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
                            <h4 class="text-white mb-1">Menunggu Persetujuan</h4>
                            <h2 class="text-white font-weight-bold mb-0">3</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-edit" style="font-size: 40px; opacity: 0.8;"></i>
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
                            <h4 class="text-white mb-1">Jadwal Ujian (Penguji)</h4>
                            <h2 class="text-white font-weight-bold mb-0">2</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-calendar-check" style="font-size: 40px; opacity: 0.8;"></i>
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
                            <h4 class="text-white mb-1">Bimbingan Selesai</h4>
                            <h2 class="text-white font-weight-bold mb-0">14</h2>
                        </div>
                        <div class="align-self-center text-center">
                            <i class="la la-check-circle" style="font-size: 40px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mahasiswa Bimbingan Aktif -->
        <div class="col-xl-8 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Mahasiswa Bimbingan Aktif</h4>
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul Skripsi</th>
                                    <th>Progress</th>
                                    <th>Terakhir Bimbingan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 4; $i++)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Bimbingan+{{ $i }}&background=random" class="rounded-circle mr-2" width="30" alt="">
                                            <div>
                                                <h6 class="mb-0">Mahasiswa Bimbingan {{ $i }}</h6>
                                                <small class="text-muted">191054{{ 100 + $i }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-truncate d-inline-block" style="max-width: 200px;">Implementasi Sistem Pakar Diagnosa Penyakit Menggunakan Metode Certainty Factor</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress mr-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-{{ $i == 1 ? 'warning' : ($i == 2 ? 'primary' : 'success') }}" role="progressbar" style="width: {{ $i * 20 + 20 }}%;" aria-valuenow="{{ $i * 20 + 20 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>{{ $i * 20 + 20 }}%</small>
                                        </div>
                                        <small class="text-muted d-block mt-1">{{ $i == 1 ? 'BAB II' : ($i == 2 ? 'BAB IV' : 'Revisi Sidang') }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::now()->subDays($i * 2)->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info text-white" title="Log Bimbingan"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-sm btn-success text-white" title="Acc Bab"><i class="fa fa-check"></i></button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Ujian (Sebagai Penguji) -->
        <div class="col-xl-4 col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-info text-white" style="background: linear-gradient(180deg, #17a2b8 0%, #117a8b 100%);">
                <div class="card-header border-bottom-0 pb-0">
                    <h4 class="card-title text-white mb-0">Jadwal Ujian Terdekat (Sebagai Penguji)</h4>
                </div>
                <div class="card-body">
                    <div class="timeline-widget">
                        <div class="p-3 bg-white text-dark rounded mb-3 shadow-sm">
                            <h6 class="text-primary font-weight-bold mb-1">Sidang Skripsi</h6>
                            <p class="mb-1 font-weight-bold">Mahasiswa: Budi Santoso</p>
                            <p class="mb-1 text-muted small"><i class="fa fa-calendar mr-1"></i> Kamis, 30 Jul 2026</p>
                            <p class="mb-2 text-muted small"><i class="fa fa-clock-o mr-1"></i> 09:00 - 11:00 WIB di Ruang R.201</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge badge-light text-primary border border-primary">Penguji 1</span>
                                <a href="#" class="btn btn-xs btn-primary">Lihat Berkas</a>
                            </div>
                        </div>

                        <div class="p-3 bg-white text-dark rounded shadow-sm">
                            <h6 class="text-info font-weight-bold mb-1">Seminar Proposal</h6>
                            <p class="mb-1 font-weight-bold">Mahasiswa: Siti Aminah</p>
                            <p class="mb-1 text-muted small"><i class="fa fa-calendar mr-1"></i> Jumat, 31 Jul 2026</p>
                            <p class="mb-2 text-muted small"><i class="fa fa-clock-o mr-1"></i> 13:30 - 15:00 WIB di Ruang Seminar B</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge badge-light text-info border border-info">Penguji 2</span>
                                <a href="#" class="btn btn-xs btn-info text-white">Lihat Berkas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        Silakan pilih Dosen pada dropdown di atas untuk melihat preview dashboard.
    </div>
    @endif
</div>
@endsection
