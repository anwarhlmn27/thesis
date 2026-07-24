@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-success text-white rounded p-3 align-items-center shadow-sm">
        <div class="col-sm-8 p-md-0">
            <div class="welcome-text">
                <h3 class="text-white mb-1"><i class="la la-wallet me-2"></i>Dashboard Staf Finance / Keuangan</h3>
                <p class="mb-0 text-white-50">Pengelolaan Verifikasi Pembayaran UKT & Clearance Financial Skripsi</p>
            </div>
        </div>
        <div class="col-sm-4 p-md-0 text-end">
            <span class="badge bg-white text-success font-w600 px-3 py-2"><i class="la la-money me-1"></i> Akses Staf Finance</span>
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
                            <p class="mb-1">Lunas UKT (Paid)</p>
                            <h3 class="text-white mb-0">{{ $stats['paid_count'] }}</h3>
                            <small>{{ $stats['paid_percentage'] }}% dari Total Mahasiswa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-danger shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-exclamation-triangle"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Belum Lunas (Unpaid)</p>
                            <h3 class="text-white mb-0">{{ $stats['unpaid_count'] }}</h3>
                            <small>Menunggak / Belum Verifikasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-warning shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-file-invoice"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Pending Proposal Approval</p>
                            <h3 class="text-white mb-0">{{ $stats['pending_proposals'] }}</h3>
                            <small>Verifikasi Keuangan Syarat Skripsi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table: Student Financial Status -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-success mb-0"><i class="la la-list-alt me-1"></i> Data Kelayakan Keuangan Mahasiswa</h5>
                    <a href="{{ route('students.index') }}" class="btn btn-xs btn-outline-success">Kelola Data Mahasiswa</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM & Nama</th>
                                    <th>Program Studi</th>
                                    <th>Semester</th>
                                    <th>Status UKT / Financial</th>
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
                                    <td>Semester {{ $st->semester }}</td>
                                    <td>
                                        @if($st->is_paid)
                                            <span class="badge bg-success"><i class="la la-check"></i> Lunas (Approved)</span>
                                        @else
                                            <span class="badge bg-danger"><i class="la la-times"></i> Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('students.edit', $st->id) }}" class="btn btn-xs {{ $st->is_paid ? 'btn-outline-secondary' : 'btn-success' }}">
                                            <i class="la la-edit"></i> {{ $st->is_paid ? 'Ubah Status' : 'Set Lunas' }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data mahasiswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Proposal Approvals for Finance -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="card-title text-success mb-0"><i class="la la-clipboard-check me-1"></i> Approval Proposal Masuk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Status Finance</th>
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
                                        <a href="{{ route('thesis-proposals.index') }}" class="btn btn-xs btn-success"><i class="la la-check"></i> Approve</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Semua proposal disetujui Finance.</td>
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
