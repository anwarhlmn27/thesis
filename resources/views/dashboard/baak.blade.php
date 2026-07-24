@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-info text-white rounded p-3 align-items-center shadow-sm">
        <div class="col-sm-8 p-md-0">
            <div class="welcome-text">
                <h3 class="text-white mb-1"><i class="la la-university me-2"></i>Dashboard Staf BAAK (Biro Administrasi Akademik)</h3>
                <p class="mb-0 text-white-50">Pengelolaan Syarat Akademik, Verifikasi Proposal, Jadwal Seminar/Sidang & Yudisium</p>
            </div>
        </div>
        <div class="col-sm-4 p-md-0 text-end">
            <span class="badge bg-white text-info font-w600 px-3 py-2"><i class="la la-user-tie me-1"></i> Akses Staf BAAK</span>
        </div>
    </div>

    <!-- Stat Widgets -->
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-primary shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-user-graduate"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Mahasiswa</p>
                            <h3 class="text-white mb-0">{{ $stats['total_students'] }}</h3>
                            <small>{{ $stats['pending_coursework'] }} Belum Clear Syarat SKS</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-warning shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-file-alt"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Pending Proposal</p>
                            <h3 class="text-white mb-0">{{ $stats['pending_proposals'] }}</h3>
                            <small>Menunggu Verifikasi BAAK</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-info shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-calendar-check-o"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Jadwal Seminar/Sidang</p>
                            <h3 class="text-white mb-0">{{ $stats['scheduled_seminars'] + $stats['scheduled_defenses'] }}</h3>
                            <small>{{ $stats['scheduled_seminars'] }} Seminar, {{ $stats['scheduled_defenses'] }} Sidang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-success shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-trophy"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total SK Yudisium</p>
                            <h3 class="text-white mb-0">{{ $stats['total_yudisiums'] }}</h3>
                            <small>Siap Cetak & Diterbitkan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <!-- Verifikasi Kelayakan Akademik Mahasiswa -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-user-check me-1"></i> Verifikasi SKS / Syarat Akademik</h5>
                    <a href="{{ route('students.index') }}" class="btn btn-xs btn-outline-primary">Kelola Mahasiswa</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM & Nama</th>
                                    <th>Prodi / Sem</th>
                                    <th>Status Akademik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingStudents as $st)
                                <tr>
                                    <td>
                                        <strong>{{ $st->user?->name }}</strong><br>
                                        <small class="text-muted">NIM: {{ $st->nim }}</small>
                                    </td>
                                    <td>{{ $st->prodi }} (Sem {{ $st->semester }})</td>
                                    <td>
                                        <span class="badge bg-danger"><i class="la la-clock"></i> Belum Verifikasi</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.edit', $st->id) }}" class="btn btn-xs btn-primary"><i class="la la-edit"></i> Verifikasi</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Semua mahasiswa telah memenuhi kelayakan akademik.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Proposals BAAK -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-file-signature me-1"></i> Verifikasi Proposal Masuk</h5>
                    <a href="{{ route('thesis-proposals.index') }}" class="btn btn-xs btn-outline-primary">Lihat Semua Proposal</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul Proposal</th>
                                    <th>Approval BAAK</th>
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
                                    <td>{{ Str::limit($prop->title, 40) }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark"><i class="la la-exclamation-circle"></i> Pending BAAK</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('thesis-proposals.index') }}" class="btn btn-xs btn-success"><i class="la la-check"></i> Process</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada proposal pending approval BAAK.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedules Overview Row -->
    <div class="row">
        <!-- Scheduled Seminars -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-calendar me-1"></i> Jadwal Seminar Proposal Aktif</h5>
                    <a href="{{ route('proposal-seminars.index') }}" class="btn btn-xs btn-outline-primary">Atur Jadwal</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Waktu & Ruangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingSeminars as $sem)
                                <tr>
                                    <td>
                                        <strong>{{ $sem->thesis?->student?->user?->name }}</strong><br>
                                        <small class="text-muted">{{ $sem->thesis?->student?->nim }}</small>
                                    </td>
                                    <td>
                                        <i class="la la-calendar text-primary"></i> {{ \Carbon\Carbon::parse($sem->seminar_date)->format('d M Y, H:i') }}<br>
                                        <i class="la la-map-marker text-danger"></i> {{ $sem->room }}
                                    </td>
                                    <td><span class="badge bg-info">Terjadwal</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada seminar proposal yang dijadwalkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduled Defenses -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-gavel me-1"></i> Jadwal Sidang Skripsi Aktif</h5>
                    <a href="{{ route('thesis-defenses.index') }}" class="btn btn-xs btn-outline-primary">Atur Sidang</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Waktu & Ruangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingDefenses as $def)
                                <tr>
                                    <td>
                                        <strong>{{ $def->thesis?->student?->user?->name }}</strong><br>
                                        <small class="text-muted">{{ $def->thesis?->student?->nim }}</small>
                                    </td>
                                    <td>
                                        <i class="la la-calendar text-primary"></i> {{ \Carbon\Carbon::parse($def->defense_date)->format('d M Y, H:i') }}<br>
                                        <i class="la la-map-marker text-danger"></i> {{ $def->room }}
                                    </td>
                                    <td><span class="badge bg-warning text-dark">{{ ucfirst($def->status) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada sidang skripsi yang dijadwalkan.</td>
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
