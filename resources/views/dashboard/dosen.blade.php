@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 bg-secondary text-white rounded p-3 align-items-center shadow-sm">
        <div class="col-sm-7 p-md-0">
            <div class="welcome-text">
                <h3 class="text-white mb-1"><i class="la la-chalkboard-teacher me-2"></i>Dashboard Dosen Pembimbing & Penguji</h3>
                <p class="mb-0 text-white-50">Monitoring Bimbingan, Pengujian Seminar/Sidang & Approval Revisi</p>
            </div>
        </div>
        <div class="col-sm-5 p-md-0 text-end">
            <!-- Lecturer Selector for Switcher Demo -->
            <form method="GET" action="{{ route('dashboard.dosen') }}" class="d-inline-block">
                <select name="lecturer_id" onchange="this.form.submit()" class="form-select form-select-sm border-0 text-dark fw-bold shadow-sm">
                    @foreach($lecturers as $l)
                    <option value="{{ $l->id }}" {{ $lecturer?->id == $l->id ? 'selected' : '' }}>
                        👨‍🏫 Demo: {{ $l->user?->name }} (NIDN: {{ $l->nidn }})
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if(!$lecturer)
    <div class="alert alert-warning">Data dosen tidak ditemukan.</div>
    @else
    <!-- Stat Widgets -->
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-primary shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-users"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Mahasiswa Bimbingan</p>
                            <h3 class="text-white mb-0">{{ $stats['total_advisees'] }}</h3>
                            <small>Aktif Dibimbing</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-warning shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-clock-o"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Log Bimbingan Pending</p>
                            <h3 class="text-white mb-0">{{ $stats['pending_logs'] }}</h3>
                            <small>Menunggu Persetujuan Dosen</small>
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
                            <p class="mb-1">Jadwal Menguji</p>
                            <h3 class="text-white mb-0">{{ $stats['proposal_exams'] + $stats['defense_exams'] }}</h3>
                            <small>{{ $stats['proposal_exams'] }} Seminar, {{ $stats['defense_exams'] }} Sidang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="widget-stat card bg-danger shadow-sm">
                <div class="card-body">
                    <div class="media">
                        <span class="me-3"><i class="la la-edit"></i></span>
                        <div class="media-body text-white">
                            <p class="mb-1">Approval Revisi Sidang</p>
                            <h3 class="text-white mb-0">{{ $stats['pending_revisions'] }}</h3>
                            <small>Revisi Menunggu Persetujuan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tables -->
    <div class="row">
        <!-- Advisees List -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-user-graduate me-1"></i> Daftar Anak Bimbingan</h5>
                    <a href="{{ route('mentoring-logs.index') }}" class="btn btn-xs btn-outline-primary">Kelola Bimbingan</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul Skripsi</th>
                                    <th>Progress Log</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adviseeTheses as $th)
                                @php
                                    $approvedCount = $th->mentoringLogs->where('status', 'approved')->count();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $th->student?->user?->name }}</strong><br>
                                        <small class="text-muted">NIM: {{ $th->student?->nim }}</small>
                                    </td>
                                    <td>{{ Str::limit($th->title, 40) }}</td>
                                    <td>
                                        <span class="badge {{ $approvedCount >= 10 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $approvedCount }} / 10 Log
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada mahasiswa bimbingan yang ditugaskan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Mentoring Logs Needing Approval -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-clock me-1"></i> Log Bimbingan Masuk (Perlu Review)</h5>
                    <a href="{{ route('mentoring-logs.index') }}" class="btn btn-xs btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mahasiswa</th>
                                    <th>Catatan / Draf</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingLogs as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->mentoring_date)->format('d M Y') }}</td>
                                    <td>
                                        <strong>{{ $log->thesis?->student?->user?->name }}</strong>
                                    </td>
                                    <td>{{ Str::limit($log->notes, 30) }}</td>
                                    <td>
                                        <a href="{{ route('mentoring-logs.edit', $log->id) }}" class="btn btn-xs btn-success"><i class="la la-check"></i> Review</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada log bimbingan pending.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam & Revision Rows -->
    <div class="row">
        <!-- Proposal Seminar Exams -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-calendar-alt me-1"></i> Jadwal Menguji Seminar Proposal</h5>
                    <a href="{{ route('proposal-examiners.index') }}" class="btn btn-xs btn-outline-primary">Detail Tim Penguji</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Waktu & Tempat</th>
                                    <th>Posisi Penguji</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proposalExams as $sem)
                                @php
                                    $ex = $sem->proposalExaminers->where('lecturer_id', $lecturer->id)->first();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $sem->thesis?->student?->user?->name }}</strong><br>
                                        <small class="text-muted">{{ $sem->thesis?->student?->nim }}</small>
                                    </td>
                                    <td>
                                        <i class="la la-clock text-primary"></i> {{ \Carbon\Carbon::parse($sem->seminar_date)->format('d M Y, H:i') }}<br>
                                        <i class="la la-map-marker text-danger"></i> {{ $sem->room }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-capitalize">{{ $ex?->position ?? 'Penguji' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada jadwal menguji seminar proposal.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Defense Revisions Approval -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-check-double me-1"></i> Approval Revisi Sidang Skripsi</h5>
                    <a href="{{ route('defense-revisions.index') }}" class="btn btn-xs btn-outline-primary">Daftar Revisi</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Deskripsi Revisi</th>
                                    <th>Aksi Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingRevisions as $rev)
                                <tr>
                                    <td>
                                        <strong>{{ $rev->thesisDefense?->thesis?->student?->user?->name }}</strong>
                                    </td>
                                    <td>{{ Str::limit($rev->description, 40) }}</td>
                                    <td>
                                        <a href="{{ route('defense-revisions.index') }}" class="btn btn-xs btn-success"><i class="la la-check"></i> Approve Revisi</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada revisi sidang yang belum disetujui.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
