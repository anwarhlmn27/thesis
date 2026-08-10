@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row mx-0 mb-4 bg-dark text-white rounded p-4 align-items-center shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="col-sm-7 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1 font-weight-bold">
                    <i class="la la-chalkboard-teacher mr-2 text-primary"></i>Dashboard Dosen
                </h4>
                <p class="mb-0 text-white-50">Monitoring Mahasiswa Bimbingan, Persetujuan Log & Jadwal Ujian</p>
            </div>
        </div>
        <div class="col-sm-5 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <div class="d-flex align-items-center">
                <span class="mr-2 text-white-50 small">Role:</span>
                <span class="badge badge-light text-dark font-weight-bold px-3 py-2" style="font-size: 13px;">
                    Dosen Pembimbing & Penguji
                    @if(isset($lecturer) && $lecturer->is_kaprodi)
                        <span class="badge badge-primary ml-1">Kaprodi</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Dosen Selector (For Admin Preview & Testing) -->
    @if(auth()->user()->role !== 'lecturer' && $lecturers->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <span class="mr-3 font-weight-bold text-dark"><i class="fa fa-user-secret text-primary mr-1"></i> Preview Dashboard Sebagai Dosen:</span>
                        <form action="{{ route('dashboard.dosen') }}" method="GET" class="d-inline-block">
                            <select name="lecturer_id" class="form-control form-control-sm default-select shadow-sm" onchange="this.form.submit()">
                                @foreach($lecturers as $lecturerItem)
                                    <option value="{{ $lecturerItem->id }}" {{ isset($lecturer) && $lecturer->id == $lecturerItem->id ? 'selected' : '' }}>
                                        {{ $lecturerItem->user?->name }} (NIDN: {{ $lecturerItem->nidn }}) {{ $lecturerItem->is_kaprodi ? '- Kaprodi' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    @if(isset($lecturer))
                        <div>
                            <span class="badge badge-primary px-3 py-2 font-weight-bold">
                                <i class="fa fa-check-circle mr-1"></i> Dosen Aktif: {{ $lecturer->user?->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($lecturer))

    <!-- Lecturer Profile Info Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-7 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3 font-weight-bold shadow-sm" style="width: 48px; height: 48px; font-size: 20px;">
                        {{ strtoupper(substr($lecturer->user?->name ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-0 text-dark font-weight-bold">{{ $lecturer->user?->name }}</h5>
                        <small class="text-muted">
                            <i class="fa fa-id-card-o mr-1"></i> NIDN: <strong>{{ $lecturer->nidn }}</strong> &nbsp;|&nbsp; 
                            <i class="fa fa-graduation-cap mr-1"></i> Prodi: <strong>{{ $lecturer->prodi }}</strong>
                        </small>
                    </div>
                </div>
                <div class="col-md-5 text-md-right mt-3 mt-md-0">
                    <div class="d-inline-flex flex-wrap gap-2">
                        <a href="{{ route('dosen.advisees.index') }}" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                            <i class="la la-users mr-1"></i> Bimbingan
                        </a>
                        <a href="{{ route('dosen.mentoring-logs.index') }}" class="btn btn-sm btn-outline-warning mr-1 mb-1 position-relative">
                            <i class="la la-book mr-1"></i> Log Bimbingan
                            @if($stats['pending_logs'] > 0)
                                <span class="badge badge-danger badge-pill ml-1">{{ $stats['pending_logs'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('dosen.exams.index') }}" class="btn btn-sm btn-outline-info mr-1 mb-1">
                            <i class="la la-calendar mr-1"></i> Jadwal Ujian
                            @if($stats['upcoming_exams'] > 0)
                                <span class="badge badge-info badge-pill ml-1">{{ $stats['upcoming_exams'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('dosen.revisions.index') }}" class="btn btn-sm btn-outline-success mb-1">
                            <i class="la la-check-circle mr-1"></i> Revisi Sidang
                            @if($stats['pending_revisions'] > 0)
                                <span class="badge badge-warning badge-pill ml-1">{{ $stats['pending_revisions'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Stat Cards -->
    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden h-100 mb-0">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-primary text-white h-100 justify-content-between align-items-center" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <div>
                            <p class="text-white-50 mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Mahasiswa Bimbingan</p>
                            <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['total_advisees']) }}</h2>
                            <small class="text-white-50"><i class="fa fa-user-circle mr-1"></i>{{ $stats['active_advisees'] }} Aktif &nbsp;•&nbsp; {{ $stats['completed_advisees'] }} Lulus</small>
                        </div>
                        <div>
                            <i class="la la-users" style="font-size: 44px; opacity: 0.85;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden h-100 mb-0">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-warning text-white h-100 justify-content-between align-items-center" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div>
                            <p class="text-white-50 mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Menunggu Persetujuan</p>
                            <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['total_pending_actions']) }}</h2>
                            <small class="text-white-50">
                                {{ $stats['pending_logs'] }} Log &nbsp;•&nbsp; {{ $stats['pending_revisions'] }} Revisi &nbsp;•&nbsp; {{ $stats['ready_for_defense'] }} ACC
                            </small>
                        </div>
                        <div>
                            <i class="la la-edit" style="font-size: 44px; opacity: 0.85;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden h-100 mb-0">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-info text-white h-100 justify-content-between align-items-center" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                        <div>
                            <p class="text-white-50 mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Jadwal Ujian (Penguji)</p>
                            <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['upcoming_exams']) }}</h2>
                            <small class="text-white-50"><i class="fa fa-calendar-check-o mr-1"></i>{{ $stats['total_exams'] }} Total Ujian Ditugaskan</small>
                        </div>
                        <div>
                            <i class="la la-calendar-check" style="font-size: 44px; opacity: 0.85;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-sm-6 mb-3">
            <div class="widget-stat card border-0 shadow-sm overflow-hidden h-100 mb-0">
                <div class="card-body p-0">
                    <div class="d-flex p-4 bg-success text-white h-100 justify-content-between align-items-center" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                        <div>
                            <p class="text-white-50 mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Bimbingan Selesai</p>
                            <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['completed_advisees']) }}</h2>
                            <small class="text-white-50"><i class="fa fa-graduation-cap mr-1"></i>Mahasiswa Telah Lulus / Yudisium</small>
                        </div>
                        <div>
                            <i class="la la-check-circle" style="font-size: 44px; opacity: 0.85;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area: Advisees Table & Sidebar -->
    <div class="row mt-2">
        <!-- Mahasiswa Bimbingan Aktif (Left Main Column) -->
        <div class="col-xl-8 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex justify-content-between align-items-center pt-4 px-4">
                    <div>
                        <h4 class="card-title text-dark mb-1 font-weight-bold">
                            <i class="la la-users text-primary mr-1"></i> Mahasiswa Bimbingan Aktif
                        </h4>
                        <p class="text-muted small mb-0">Daftar mahasiswa bimbingan Anda beserta status progress & log bimbingan aktual.</p>
                    </div>
                    <a href="{{ route('dosen.advisees.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua ({{ $activeAdviseesList->count() }}) <i class="fa fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 170px;">Mahasiswa</th>
                                    <th style="min-width: 220px;">Judul Skripsi</th>
                                    <th style="min-width: 150px;">Progress Bimbingan</th>
                                    <th style="min-width: 130px;">Terakhir Log</th>
                                    <th style="min-width: 110px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeAdviseesList as $thesis)
                                    @php
                                        $advisorRole = $thesis->thesisAdvisors->where('lecturer_id', $lecturer->id)->first();
                                        $myLogs = $advisorRole ? $thesis->mentoringLogs->where('thesis_advisor_id', $advisorRole->id) : collect();
                                        $approvedLogsCount = $myLogs->where('status', 'approved')->count();
                                        $lastLog = $myLogs->sortByDesc('mentoring_date')->first();
                                        
                                        // Calculate percentage progress based on minimum 10 required logs
                                        $percent = min(100, round(($approvedLogsCount / 10) * 100));
                                        
                                        // Determine current stage
                                        if (in_array($thesis->status, ['graduated', 'revision_approved', 'yudisium_ready'])) {
                                            $stageLabel = 'Lulus / Selesai';
                                            $stageBadge = 'badge-success';
                                            $progressColor = 'bg-success';
                                        } elseif ($advisorRole && $advisorRole->is_approved_for_defense) {
                                            $stageLabel = 'ACC Sidang (Siap Ujian)';
                                            $stageBadge = 'badge-info';
                                            $progressColor = 'bg-info';
                                        } elseif ($approvedLogsCount >= 10) {
                                            $stageLabel = 'Siap ACC Sidang (>= 10 Log)';
                                            $stageBadge = 'badge-primary';
                                            $progressColor = 'bg-primary';
                                        } elseif ($approvedLogsCount >= 7) {
                                            $stageLabel = 'BAB IV - V';
                                            $stageBadge = 'badge-primary';
                                            $progressColor = 'bg-primary';
                                        } elseif ($approvedLogsCount >= 4) {
                                            $stageLabel = 'BAB II - III';
                                            $stageBadge = 'badge-info';
                                            $progressColor = 'bg-info';
                                        } elseif ($approvedLogsCount >= 1) {
                                            $stageLabel = 'BAB I';
                                            $stageBadge = 'badge-warning';
                                            $progressColor = 'bg-warning';
                                        } else {
                                            $stageLabel = 'Belum Ada Log Disetujui';
                                            $stageBadge = 'badge-secondary';
                                            $progressColor = 'bg-secondary';
                                        }

                                        // Role badge
                                        $roleText = 'Pembimbing';
                                        if ($advisorRole) {
                                            $roleText = $advisorRole->type === 'primary' ? 'Pembimbing 1' : 'Pembimbing 2';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center mr-2 font-weight-bold shadow-xs" style="width: 38px; height: 38px; font-size: 14px; border: 1px solid #e2e8f0;">
                                                    {{ strtoupper(substr($thesis->student?->user?->name ?? 'M', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-bold text-dark">{{ $thesis->student?->user?->name ?? 'Mahasiswa' }}</h6>
                                                    <small class="text-muted d-block">NIM: {{ $thesis->student?->nim ?? '-' }}</small>
                                                    <span class="badge badge-xs badge-light text-muted border mt-1">{{ $thesis->student?->prodi ?? 'Prodi' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 250px;">
                                                <p class="mb-1 text-dark text-truncate font-weight-500" title="{{ $thesis->title }}">
                                                    {{ $thesis->title }}
                                                </p>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="badge badge-xs {{ $advisorRole && $advisorRole->type === 'primary' ? 'badge-primary' : 'badge-info' }} mr-1">
                                                        {{ $roleText }}
                                                    </span>
                                                    @if($advisorRole && $advisorRole->is_approved_for_defense)
                                                        <span class="badge badge-xs badge-success"><i class="fa fa-check"></i> ACC Sidang</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="progress flex-grow-1 mr-2" style="height: 7px;">
                                                    <div class="progress-bar {{ $progressColor }} progress-bar-striped" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="font-weight-bold text-dark">{{ $percent }}%</small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">{{ $approvedLogsCount }}/10 Log Disetujui</small>
                                            </div>
                                            <span class="badge {{ $stageBadge }} badge-xs mt-1">{{ $stageLabel }}</span>
                                        </td>
                                        <td>
                                            @if($lastLog)
                                                <div class="text-dark font-weight-500">
                                                    <i class="fa fa-calendar-o text-muted mr-1"></i>{{ \Carbon\Carbon::parse($lastLog->mentoring_date)->format('d M Y') }}
                                                </div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($lastLog->mentoring_date)->diffForHumans() }}</small>
                                            @else
                                                <span class="badge badge-light text-muted">Belum ada bimbingan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('dosen.mentoring-logs.index') }}" class="btn btn-outline-primary" title="Buka Log Bimbingan">
                                                    <i class="fa fa-list-alt"></i>
                                                </a>
                                                @if($approvedLogsCount >= 10 && $advisorRole)
                                                    @if(!$advisorRole->is_approved_for_defense)
                                                        <form action="{{ route('dosen.advisees.approve', $thesis->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin menyetujui kelayakan sidang untuk mahasiswa ini?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success text-white" title="ACC Kelayakan Sidang">
                                                                <i class="fa fa-check"></i> ACC
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('dosen.advisees.approve', $thesis->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan persetujuan ACC sidang untuk mahasiswa ini?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger" title="Batalkan ACC Sidang">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="la la-user-slash mb-3" style="font-size: 48px; opacity: 0.4;"></i>
                                                <h6 class="text-muted">Belum Ada Mahasiswa Bimbingan</h6>
                                                <p class="small text-muted mb-0">Anda belum ditugaskan sebagai dosen pembimbing oleh Kaprodi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Antrean Persetujuan Log & Revisi (If Any Pending) -->
            @if($pendingLogs->isNotEmpty() || $pendingRevisions->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title text-dark mb-1 font-weight-bold">
                            <i class="la la-clock text-warning mr-1"></i> Antrean Persetujuan Menunggu Respon
                        </h4>
                        <p class="text-muted small mb-0">Tinjau log bimbingan & revisi sidang yang diajukan oleh mahasiswa.</p>
                    </div>
                    <span class="badge badge-warning font-weight-bold px-3 py-2">
                        {{ $pendingLogs->count() + $pendingRevisions->count() }} Menunggu Tindakan
                    </span>
                </div>
                <div class="card-body px-4">
                    <!-- Nav Tabs for Pending Items -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        @if($pendingLogs->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#pending-logs-tab">
                                <i class="la la-book mr-1"></i> Log Bimbingan 
                                <span class="badge badge-warning badge-pill ml-1">{{ $pendingLogs->count() }}</span>
                            </a>
                        </li>
                        @endif
                        @if($pendingRevisions->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link {{ $pendingLogs->isEmpty() ? 'active' : '' }}" data-bs-toggle="tab" href="#pending-revisions-tab">
                                <i class="la la-check-circle mr-1"></i> Revisi Sidang 
                                <span class="badge badge-danger badge-pill ml-1">{{ $pendingRevisions->count() }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <!-- Pending Mentoring Logs -->
                        @if($pendingLogs->isNotEmpty())
                        <div class="tab-pane fade show active" id="pending-logs-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Tanggal</th>
                                            <th>Catatan Mahasiswa</th>
                                            <th>Dokumen</th>
                                            <th class="text-center">Aksi Cepat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingLogs as $log)
                                        <tr>
                                            <td>
                                                <strong>{{ $log->thesis?->student?->user?->name }}</strong><br>
                                                <small class="text-muted">NIM: {{ $log->thesis?->student?->nim }}</small>
                                            </td>
                                            <td>
                                                <i class="fa fa-calendar text-muted mr-1"></i>
                                                {{ \Carbon\Carbon::parse($log->mentoring_date)->format('d M Y') }}
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $log->notes }}">
                                                    {{ $log->notes }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($log->document_path)
                                                    <a href="{{ Storage::url($log->document_path) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                                        <i class="fa fa-download"></i> Unduh
                                                    </a>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('dosen.mentoring-logs.index') }}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-check-square mr-1"></i> Review Log
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Pending Revisions -->
                        @if($pendingRevisions->isNotEmpty())
                        <div class="tab-pane fade {{ $pendingLogs->isEmpty() ? 'show active' : '' }}" id="pending-revisions-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Catatan Revisi</th>
                                            <th>File Revisi</th>
                                            <th>Tanggal Upload</th>
                                            <th class="text-center">Aksi Cepat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingRevisions as $rev)
                                        <tr>
                                            <td>
                                                <strong>{{ $rev->thesisDefense?->thesis?->student?->user?->name }}</strong><br>
                                                <small class="text-muted">NIM: {{ $rev->thesisDefense?->thesis?->student?->nim }}</small>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $rev->description }}">
                                                    {{ $rev->description ?? 'Tidak ada catatan khusus.' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($rev->revision_file_path)
                                                    <a href="{{ Storage::url($rev->revision_file_path) }}" target="_blank" class="btn btn-xs btn-info">
                                                        <i class="fa fa-file-pdf-o"></i> Lihat File
                                                    </a>
                                                @else
                                                    <span class="badge badge-warning">Belum diunggah</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $rev->updated_at ? $rev->updated_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('dosen.revisions.approve', $rev->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin menyetujui revisi ini?')">
                                                    @csrf
                                                    <input type="hidden" name="is_approved" value="1">
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-check mr-1"></i> Approve
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Jadwal Ujian Terdekat (Sebagai Penguji) -->
        <div class="col-xl-4 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #06b6d4 !important;">
                <div class="card-header bg-white border-bottom-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-dark font-weight-bold mb-1">
                            <i class="la la-calendar-check text-info mr-1"></i> Jadwal Ujian Penguji
                        </h5>
                        <p class="text-muted small mb-0">Jadwal Seminar Proposal & Sidang Skripsi</p>
                    </div>
                    <a href="{{ route('dosen.exams.index') }}" class="btn btn-xs btn-outline-info">Semua</a>
                </div>
                <div class="card-body px-4 pt-3">
                    @forelse($upcomingExams as $exam)
                        <div class="p-3 bg-light rounded mb-3 shadow-xs border border-light" style="transition: all 0.2s ease;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge badge-{{ $exam->type_color }} px-2 py-1" style="font-size: 11px;">
                                    {{ $exam->type_label }}
                                </span>
                                <span class="badge badge-light text-dark border">
                                    {{ $exam->role_label }}
                                </span>
                            </div>

                            <h6 class="text-dark font-weight-bold mb-1">{{ $exam->student_name }}</h6>
                            <small class="text-muted d-block mb-2">NIM: {{ $exam->student_nim }}</small>
                            
                            <p class="text-muted small mb-2 text-truncate font-italic" title="{{ $exam->thesis_title }}">
                                "{{ $exam->thesis_title }}"
                            </p>

                            <div class="bg-white p-2 rounded mb-2 border border-light small">
                                <div class="mb-1 text-dark">
                                    <i class="fa fa-calendar text-primary mr-1"></i>
                                    <strong>{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('d M Y, H:i') . ' WIB' : 'Waktu TBA' }}</strong>
                                </div>
                                <div class="text-muted">
                                    <i class="fa fa-map-marker text-danger mr-1"></i> {{ $exam->room }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                <div>
                                    @if($exam->is_evaluated)
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fa fa-check-circle mr-1"></i> Sudah Dinilai
                                            @if($exam->score !== null)
                                                ({{ $exam->score }})
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fa fa-clock-o mr-1"></i> Belum Dinilai
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    @if($exam->kind === 'proposal')
                                        <button type="button" class="btn btn-xs {{ $exam->is_evaluated ? 'btn-outline-success' : 'btn-info text-white' }} eval-btn"
                                                data-id="{{ $exam->id }}"
                                                data-student="{{ $exam->student_name }}"
                                                data-title="{{ $exam->thesis_title }}"
                                                data-status="{{ $exam->eval_status ?? 'pending' }}"
                                                data-notes="{{ $exam->notes ?? '' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#evalModal">
                                            <i class="fa {{ $exam->is_evaluated ? 'fa-check' : 'fa-edit' }}"></i> 
                                            {{ $exam->is_evaluated ? 'Edit Nilai' : 'Beri Nilai' }}
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-xs {{ $exam->is_evaluated ? 'btn-outline-success' : 'btn-primary' }} eval-defense-btn"
                                                data-id="{{ $exam->id }}"
                                                data-student="{{ $exam->student_name }}"
                                                data-title="{{ $exam->thesis_title }}"
                                                data-score="{{ $exam->score ?? '' }}"
                                                data-notes="{{ $exam->notes ?? '' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#evalDefenseModal">
                                            <i class="fa {{ $exam->is_evaluated ? 'fa-check' : 'fa-edit' }}"></i> 
                                            {{ $exam->is_evaluated ? 'Edit Nilai' : 'Beri Nilai' }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="la la-calendar-times-o mb-2 text-muted" style="font-size: 40px; opacity: 0.5;"></i>
                            <h6 class="text-muted font-weight-bold mb-1">Tidak Ada Jadwal Ujian</h6>
                            <p class="small text-muted mb-0">Anda belum memiliki jadwal seminar proposal atau sidang skripsi terdekat sebagai penguji.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info shadow-sm">
        <i class="fa fa-info-circle mr-2"></i> Silakan pilih Dosen pada dropdown di atas untuk melihat preview dashboard.
    </div>
    @endif
</div>

<!-- Modal Evaluasi Seminar Proposal -->
<div class="modal fade" id="evalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold"><i class="la la-pencil-square mr-1"></i> Penilaian Seminar Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="evalForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Mahasiswa</label>
                        <input type="text" id="eval_student" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Judul Proposal</label>
                        <textarea id="eval_title" class="form-control" rows="2" readonly></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Keputusan Kelayakan <span class="text-danger">*</span></label>
                        <select name="status" id="eval_status" class="form-control form-control-lg" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="passed">Lulus (Layak dilanjutkan ke Skripsi)</option>
                            <option value="revision">Revisi (Layak dengan perbaikan)</option>
                            <option value="failed">Tidak Lulus (Harus mengulang/ganti judul)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Catatan Perbaikan (Komentar / Revisi)</label>
                        <textarea name="notes" id="eval_notes" class="form-control" rows="4" placeholder="Tuliskan catatan revisi, saran, atau masukan untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Evaluasi Sidang Skripsi -->
<div class="modal fade" id="evalDefenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold"><i class="la la-gavel mr-1"></i> Penilaian Sidang Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="evalDefenseForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Mahasiswa</label>
                        <input type="text" id="eval_defense_student" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Judul Skripsi</label>
                        <textarea id="eval_defense_title" class="form-control" rows="2" readonly></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Nilai Sidang (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="score" id="eval_defense_score" class="form-control form-control-lg" min="0" max="100" step="0.01" required placeholder="Masukkan nilai angka (misal: 85.50)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Catatan Perbaikan / Revisi Pasca Sidang</label>
                        <textarea name="notes" id="eval_defense_notes" class="form-control" rows="4" placeholder="Tuliskan catatan revisi, perbaikan, atau masukan untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Evaluasi Seminar Proposal Modal Handler
        const evalBtns = document.querySelectorAll('.eval-btn');
        evalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const student = this.getAttribute('data-student');
                const title = this.getAttribute('data-title');
                const status = this.getAttribute('data-status');
                const notes = this.getAttribute('data-notes');
                
                document.getElementById('evalForm').action = `/dosen/exams/proposal/${id}/evaluate`;
                document.getElementById('eval_student').value = student;
                document.getElementById('eval_title').value = title;
                document.getElementById('eval_notes').value = notes;
                
                const statusSelect = document.getElementById('eval_status');
                if (status && status !== 'pending') {
                    statusSelect.value = status;
                } else {
                    statusSelect.value = '';
                }
            });
        });

        // Evaluasi Sidang Skripsi Modal Handler
        const evalDefenseBtns = document.querySelectorAll('.eval-defense-btn');
        evalDefenseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const student = this.getAttribute('data-student');
                const title = this.getAttribute('data-title');
                const score = this.getAttribute('data-score');
                const notes = this.getAttribute('data-notes');
                
                document.getElementById('evalDefenseForm').action = `/dosen/exams/defense/${id}/evaluate`;
                document.getElementById('eval_defense_student').value = student;
                document.getElementById('eval_defense_title').value = title;
                document.getElementById('eval_defense_score').value = score;
                document.getElementById('eval_defense_notes').value = notes;
            });
        });
    });
</script>
@endsection
