@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-warning text-dark rounded p-3 align-items-center shadow-sm">
        <div class="col-sm-8 p-md-0">
            <div class="welcome-text">
                <h3 class="text-dark mb-1"><i class="la la-book-reader me-2"></i>Dashboard Staf Perpustakaan</h3>
                <p class="mb-0 text-dark-50">Pengelolaan Verifikasi Bebas Pustaka & Penyerahan Karya Ilmiah Skripsi</p>
            </div>
        </div>
        <div class="col-sm-4 p-md-0 text-end">
            <span class="badge bg-dark text-warning font-w600 px-3 py-2"><i class="la la-book me-1"></i> Akses Staf Perpustakaan</span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-primary shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-users"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Mahasiswa</p>
                            <h3 class="text-white mb-0">{{ $stats['total_students'] }}</h3>
                            <small>Terdaftar di Sistem</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-success shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-check-circle"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Bebas Perpustakaan</p>
                            <h3 class="text-white mb-0">{{ $stats['clear_count'] }}</h3>
                            <small>{{ $stats['clear_percentage'] }}% Mahasiswa Bebas Pustaka</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-warning shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-hourglass-half"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Pending Bebas Pustaka</p>
                            <h3 class="text-white mb-0">{{ $stats['pending_count'] }}</h3>
                            <small>Pinjaman Belum Kembali</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-info shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-file-pdf"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Softcopy Skripsi Final</p>
                            <h3 class="text-white mb-0">{{ $stats['final_submissions'] }}</h3>
                            <small>File Skripsi Diterima</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table: Student Library Clearance -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-warning mb-0"><i class="la la-tasks me-1"></i> Status Bebas Perpustakaan Mahasiswa</h5>
                    <a href="{{ route('students.index') }}" class="btn btn-xs btn-outline-warning">Kelola Mahasiswa</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM & Nama</th>
                                    <th>Program Studi</th>
                                    <th>Status Bebas Pustaka</th>
                                    <th>Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $st)
                                <tr>
                                    <td>
                                        <strong>{{ $st->user?->name }}</strong><br>
                                        <small class="text-muted">NIM: {{ $st->nim }}</small>
                                    </td>
                                    <td>{{ $st->prodi }}</td>
                                    <td>
                                        @if($st->is_library_clear)
                                            <span class="badge bg-success"><i class="la la-check"></i> Bebas Pustaka (Clear)</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="la la-times"></i> Menunggu Bebas Pustaka</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('students.edit', $st->id) }}" class="btn btn-xs {{ $st->is_library_clear ? 'btn-outline-secondary' : 'btn-warning' }}">
                                            <i class="la la-edit"></i> {{ $st->is_library_clear ? 'Ubah Status' : 'Set Bebas Pustaka' }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada data mahasiswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Proposal Approvals for Library -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="card-title text-warning mb-0"><i class="la la-clipboard-check me-1"></i> Verifikasi Syarat Proposal</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Status Library</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingProposals as $prop)
                                <tr>
                                    <td>
                                        <strong>{{ $prop->student?->user?->name }}</strong><br>
                                        <small class="text-muted">{{ $prop->student?->nim }}</small>
                                    </td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="{{ route('thesis-proposals.index') }}" class="btn btn-xs btn-warning"><i class="la la-check"></i> Approve</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Semua proposal disetujui Perpustakaan.</td>
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
