@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 text-white rounded p-3 align-items-center shadow-sm" style="background: linear-gradient(90deg, #28a745 0%, #20c997 100%);">
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
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Menunggu Verifikasi</h5>
                        <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['pending_verification']) }}</h2>
                        <small class="text-white-50">{{ $stats['pending_proposals'] }} Proposal | {{ $stats['unpaid_count'] }} Mahasiswa UKT</small>
                    </div>
                    <div>
                        <i class="la la-hourglass-half text-white" style="font-size: 45px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Telah Diverifikasi</h5>
                        <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['total_verified']) }}</h2>
                        <small class="text-white-50">{{ $stats['paid_count'] }} Lunas UKT | {{ $stats['approved_proposals'] }} Proposal Lunas</small>
                    </div>
                    <div>
                        <i class="la la-check-circle text-white" style="font-size: 45px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Tagihan Belum Lunas</h5>
                        <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['unpaid_count']) }}</h2>
                        <small class="text-white-50">Dari total {{ number_format($stats['total_students']) }} mahasiswa terdaftar</small>
                    </div>
                    <div>
                        <i class="la la-file-invoice-dollar text-white" style="font-size: 45px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Portal Validasi Keuangan</h5>
                        <p class="text-muted mb-0">Kelola dan verifikasi status pembayaran UKT serta biaya seminar proposal mahasiswa.</p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <a href="{{ route('finance.clearance.index') }}" class="btn btn-success"><i class="la la-check-square mr-1"></i> Buka Portal Clearance Finance</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Antrean Verifikasi Keuangan (Actual Data) -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Antrean Verifikasi Keuangan</h4>
                    <span class="badge badge-warning text-white">{{ $stats['pending_verification'] }} Total Pending</span>
                </div>
                <div class="card-body">
                    <!-- Nav Tabs for Proposals vs UKT -->
                    <ul class="nav nav-pills mb-3" id="financeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-proposals" data-bs-toggle="pill" data-bs-target="#pane-proposals" type="button" role="tab">
                                <i class="la la-file-alt mr-1"></i> Biaya Seminar Proposal ({{ $pendingProposals->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-ukt" data-bs-toggle="pill" data-bs-target="#pane-ukt" type="button" role="tab">
                                <i class="la la-money mr-1"></i> Pembayaran UKT Mahasiswa ({{ $unpaidStudents->count() }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="financeTabContent">
                        <!-- Tab Pane 1: Proposal Payments -->
                        <div class="tab-pane fade show active" id="pane-proposals" role="tabpanel">
                            @if($pendingProposals->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Judul Proposal</th>
                                            <th>File Draf</th>
                                            <th>Status Pembayaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingProposals as $proposal)
                                        <tr>
                                            <td>
                                                <strong>{{ $proposal->thesis->student->user->name ?? '-' }}</strong><br>
                                                <small class="text-muted">NIM: {{ $proposal->thesis->student->nim ?? '-' }} | {{ $proposal->thesis->student->prodi ?? '-' }}</small>
                                            </td>
                                            <td>{{ $proposal->thesis->title ?? '-' }}</td>
                                            <td>
                                                @if($proposal->proposal_file_path)
                                                    <a href="{{ Storage::url($proposal->proposal_file_path) }}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-file-pdf mr-1"></i> Lihat Draf</a>
                                                @else
                                                    <span class="text-muted small">Tidak ada file</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-light text-warning"><i class="fa fa-clock-o mr-1"></i> Belum Lunas</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('finance.clearance.update_proposal', $proposal->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Tandai biaya seminar proposal mahasiswa ini telah lunas?">
                                                    @csrf
                                                    <input type="hidden" name="is_finance_approved" value="1">
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check mr-1"></i> Setujui Lunas</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="la la-check-circle text-success" style="font-size: 48px;"></i>
                                <h5 class="mt-3 text-dark">Tidak Ada Antrean Pembayaran Proposal</h5>
                                <p class="text-muted mb-0">Semua biaya seminar proposal telah diverifikasi lunas.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Tab Pane 2: UKT Payments -->
                        <div class="tab-pane fade" id="pane-ukt" role="tabpanel">
                            @if($unpaidStudents->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Prodi / Semester</th>
                                            <th>Status UKT</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unpaidStudents as $student)
                                        <tr>
                                            <td>
                                                <strong>{{ $student->user->name ?? '-' }}</strong><br>
                                                <small class="text-muted">NIM: {{ $student->nim }}</small>
                                            </td>
                                            <td>{{ $student->prodi }} / Semester {{ $student->semester }}</td>
                                            <td>
                                                <span class="badge badge-light text-danger"><i class="fa fa-times-circle mr-1"></i> Belum Lunas UKT</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('finance.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Tandai mahasiswa ini telah melunasi seluruh pembayaran UKT?">
                                                    @csrf
                                                    <input type="hidden" name="is_paid" value="1">
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check mr-1"></i> Setujui Lunas</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="la la-check-circle text-success" style="font-size: 48px;"></i>
                                <h5 class="mt-3 text-dark">Seluruh Mahasiswa Telah Lunas UKT</h5>
                                <p class="text-muted mb-0">Tidak ada mahasiswa yang memiliki tunggakan UKT aktif.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
