@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 text-white rounded p-3 align-items-center shadow-sm" style="background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);">
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
    <div class="row mb-4">
        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white border-0 shadow-sm h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Total Mahasiswa</h5>
                        <h2 class="text-white font-weight-bold mb-0">{{ number_format($stats['total_students']) }}</h2>
                    </div>
                    <div>
                        <i class="la la-users text-white" style="font-size: 42px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="card bg-warning text-white border-0 shadow-sm h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Antrean Validasi BAAK</h5>
                        <h2 class="text-white font-weight-bold mb-0">{{ number_format($stats['pending_coursework']) }}</h2>
                    </div>
                    <div>
                        <i class="la la-file-alt text-white" style="font-size: 42px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="card bg-info text-white border-0 shadow-sm h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Skripsi Aktif</h5>
                        <h2 class="text-white font-weight-bold mb-0">{{ number_format($stats['active_theses']) }}</h2>
                    </div>
                    <div>
                        <i class="la la-book text-white" style="font-size: 42px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="card bg-success text-white border-0 shadow-sm h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Pendaftar Yudisium</h5>
                        <h2 class="text-white font-weight-bold mb-0">{{ number_format($stats['total_yudisium_students']) }}</h2>
                    </div>
                    <div>
                        <i class="la la-graduation-cap text-white" style="font-size: 42px; opacity: 0.85;"></i>
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
                        <a href="{{ route('baak.clearance.index') }}" class="btn btn-outline-primary mr-2 mb-2"><i class="la la-check-square mr-1"></i> Validasi Nilai & Akademik</a>
                        <a href="{{ route('yudisiums.index') }}" class="btn btn-outline-success mr-2 mb-2"><i class="la la-graduation-cap mr-1"></i> Kelola Periode Yudisium</a>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-info mr-2 mb-2"><i class="la la-user-plus mr-1"></i> Kelola Data Mahasiswa</a>
                        <a href="{{ route('thesis-defenses.index') }}" class="btn btn-outline-warning mr-2 mb-2"><i class="la la-calendar mr-1"></i> Jadwal Sidang</a>
                        <a href="{{ route('proposal-seminars.index') }}" class="btn btn-outline-secondary mr-2 mb-2"><i class="la la-chalkboard-teacher mr-1"></i> Jadwal Seminar Proposal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Antrean Validasi Akademik (BAAK) -->
        <div class="col-xl-8 col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Antrean Validasi Akademik (Kelayakan SKS)</h4>
                    <span class="badge badge-warning text-white">{{ $stats['pending_coursework'] }} Pending</span>
                </div>
                <div class="card-body">
                    @if($pendingStudents->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Program Studi</th>
                                    <th>Status Kelayakan SKS</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingStudents as $s)
                                <tr>
                                    <td><strong>{{ $s->nim }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-light text-primary rounded-circle mr-2 text-center" style="width: 32px; height: 32px; line-height: 32px; font-weight: bold;">
                                                {{ strtoupper(substr($s->user?->name ?? 'M', 0, 2)) }}
                                            </div>
                                            <span>{{ $s->user?->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $s->prodi }} (Sem. {{ $s->semester }})</td>
                                    <td>
                                        <span class="badge badge-light text-warning"><i class="fa fa-clock-o mr-1"></i> Belum Memenuhi SKS</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('baak.clearance.index') }}" class="btn btn-sm btn-primary">Validasi</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="la la-check-circle text-success" style="font-size: 50px;"></i>
                        <h5 class="mt-3 text-dark">Tidak Ada Antrean Validasi</h5>
                        <p class="text-muted mb-0">Semua data mahasiswa telah divalidasi kelayakan akademiknya.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Periode Yudisium Aktif -->
        <div class="col-xl-4 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Jadwal Yudisium</h4>
                    <a href="{{ route('yudisiums.index') }}" class="btn btn-xs btn-outline-primary">Kelola</a>
                </div>
                <div class="card-body">
                    @if($yudisiums->isNotEmpty())
                        @foreach($yudisiums as $yud)
                        <div class="alert alert-success border-0 shadow-sm mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h5 class="alert-heading font-weight-bold text-success mb-1">No SK: {{ $yud->sk_number }}</h5>
                                <span class="badge badge-sm badge-success">{{ ucfirst($yud->status) }}</span>
                            </div>
                            <p class="mb-1 small"><i class="fa fa-graduation-cap mr-1"></i> Tahun Akademik: <strong>{{ $yud->academic_year ?? '-' }}</strong></p>
                            <p class="mb-2 small"><i class="fa fa-calendar mr-1"></i> Tanggal Kelulusan: <strong>{{ $yud->graduation_date ? \Carbon\Carbon::parse($yud->graduation_date)->format('d M Y') : '-' }}</strong></p>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">Peserta: <strong>{{ $yud->students_count }} Mahasiswa</strong></span>
                                <div>
                                    <a href="{{ route('yudisiums.print', $yud->id) }}" target="_blank" class="btn btn-xs btn-outline-success mr-1"><i class="la la-print"></i> Cetak</a>
                                    <a href="{{ route('yudisiums.index') }}" class="btn btn-xs btn-success text-white">Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-secondary border-0 text-center py-4">
                            <i class="la la-graduation-cap text-muted" style="font-size: 40px;"></i>
                            <h6 class="mt-2 mb-1 text-dark font-weight-bold">Belum Ada SK Yudisium</h6>
                            <p class="mb-3 text-muted small">Belum ada draft atau periode SK Yudisium yang dibuat.</p>
                            <a href="{{ route('yudisiums.index') }}" class="btn btn-sm btn-primary"><i class="la la-plus mr-1"></i> Buat Periode Yudisium</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
