@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Title & Role Switcher Banner -->
    <div class="row page-titles mx-0 mb-4 bg-primary text-white rounded p-3 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h3 class="text-white mb-1"><i class="la la-shield me-2"></i>Dashboard Utama & Overview System</h3>
                <p class="mb-0 text-white-50">Sistem Informasi Manajemen Tugas Akhir & Skripsi Multi-Role</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 text-end">
            <span class="badge bg-white text-primary px-3 py-2 font-w600"><i class="la la-user-shield me-1"></i> Mode Super Admin</span>
        </div>
    </div>

    <!-- Role Quick Navigation Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 text-secondary font-w600"><i class="la la-exchange-alt me-1"></i> Pilih Dashboard Sesuai Akses Peran:</h5>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('dashboard.mahasiswa') }}" class="card text-decoration-none shadow-sm hover-top border-0 bg-gradient-primary text-white h-100">
                <div class="card-body text-center p-3">
                    <i class="la la-user-graduate la-3x mb-2"></i>
                    <h6 class="text-white mb-1 font-w600">Mahasiswa</h6>
                    <small class="text-white-50">Progres & Bimbingan</small>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('dashboard.baak') }}" class="card text-decoration-none shadow-sm hover-top border-0 bg-info text-white h-100">
                <div class="card-body text-center p-3">
                    <i class="la la-university la-3x mb-2"></i>
                    <h6 class="text-white mb-1 font-w600">Staf BAAK</h6>
                    <small class="text-white-50">Akademik & Yudisium</small>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('dashboard.finance') }}" class="card text-decoration-none shadow-sm hover-top border-0 bg-success text-white h-100">
                <div class="card-body text-center p-3">
                    <i class="la la-wallet la-3x mb-2"></i>
                    <h6 class="text-white mb-1 font-w600">Staf Finance</h6>
                    <small class="text-white-50">Pembayaran & UKT</small>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('dashboard.perpustakaan') }}" class="card text-decoration-none shadow-sm hover-top border-0 bg-warning text-white h-100">
                <div class="card-body text-center p-3">
                    <i class="la la-book-reader la-3x mb-2"></i>
                    <h6 class="text-white mb-1 font-w600">Staf Perpustakaan</h6>
                    <small class="text-white-50">Bebas Pustaka</small>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('dashboard.dosen') }}" class="card text-decoration-none shadow-sm hover-top border-0 bg-secondary text-white h-100">
                <div class="card-body text-center p-3">
                    <i class="la la-chalkboard-teacher la-3x mb-2"></i>
                    <h6 class="text-white mb-1 font-w600">Dosen / Kaprodi</h6>
                    <small class="text-white-50">Bimbingan & Penguji</small>
                </div>
            </a>
        </div>
    </div>

    <!-- Stat Widgets Row -->
    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card bg-primary shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-users"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Mahasiswa</p>
                            <h3 class="text-white mb-0">{{ $stats['total_students'] }}</h3>
                            <small>{{ $stats['coursework_completed'] }} Lulus Syarat Akademik</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card bg-success shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-money"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Bebas Keuangan</p>
                            <h3 class="text-white mb-0">{{ $stats['finance_paid'] }} / {{ $stats['total_students'] }}</h3>
                            <small>{{ $stats['total_students'] > 0 ? round(($stats['finance_paid']/$stats['total_students'])*100) : 0 }}% Mahasiswa Lunas UKT</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card bg-warning shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-book"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Bebas Pustaka</p>
                            <h3 class="text-white mb-0">{{ $stats['library_clear'] }} / {{ $stats['total_students'] }}</h3>
                            <small>{{ $stats['total_students'] > 0 ? round(($stats['library_clear']/$stats['total_students'])*100) : 0 }}% Mahasiswa Bebas Pustaka</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="widget-stat card bg-info shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-graduation-cap"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Yudisium</p>
                            <h3 class="text-white mb-0">{{ $stats['total_yudisiums'] }}</h3>
                            <small>SK Kelulusan Diterbitkan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Workflow Summary -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-stream me-2"></i>Daftar Skripsi Terbaru</h5>
                    <a href="{{ route('theses.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Data</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM / Mahasiswa</th>
                                    <th>Judul Skripsi</th>
                                    <th>Status Workflow</th>
                                    <th>Detail & Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTheses as $t)
                                <tr>
                                    <td>
                                        <strong>{{ $t->student?->user?->name ?? 'Mahasiswa' }}</strong><br>
                                        <small class="text-muted">NIM: {{ $t->student?->nim ?? '-' }}</small>
                                    </td>
                                    <td>{{ Str::limit($t->title, 65) }}</td>
                                    <td>
                                        <span class="badge bg-soft-info text-info font-w600 px-3 py-2">
                                            {{ strtoupper(str_replace('_', ' ', $t->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('theses.show', $t->id) }}" class="btn btn-xs sharp btn-primary"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data skripsi aktif.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
