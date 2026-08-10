@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-7 p-md-0">
            <div class="welcome-text">
                <h3 class="text-white mb-1"><i class="la la-user-graduate me-2 text-white"></i>Dashboard Portal Mahasiswa</h3>
                <p class="mb-0 text-white-50">Monitoring Alur Pendaftaran, Bimbingan & Kelulusan Skripsi</p>
            </div>
        </div>
        <div class="col-sm-5 p-md-0 text-end">
            <!-- Student Selector for Switcher Demo (Only for Admin/Staff) -->
            @if(auth()->user()->role !== 'student' && $students->isNotEmpty())
            <form method="GET" action="{{ route('dashboard.mahasiswa') }}" class="d-inline-block">
                <select name="student_id" onchange="this.form.submit()" class="form-select form-select-sm border-0 text-dark fw-bold shadow-sm">
                    @foreach($students as $s)
                    <option value="{{ $s->id }}" {{ $student?->id == $s->id ? 'selected' : '' }}>
                        👤 Demo: {{ $s->user?->name }} (NIM: {{ $s->nim }})
                    </option>
                    @endforeach
                </select>
            </form>
            @endif
        </div>
    </div>

    @if(!$student)
    <div class="alert alert-warning">Data mahasiswa tidak ditemukan. Silakan pilih akun mahasiswa lain.</div>
    @else
    <!-- Student Information Summary Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center border-end">
                    <div class="avatar avatar-xl bg-light text-primary rounded-circle mx-auto p-3 mb-2" style="width: 80px; height: 80px;">
                        <i class="la la-user-graduate la-3x"></i>
                    </div>
                    <h5 class="mb-0 text-primary">{{ $student->user?->name }}</h5>
                    <span class="badge bg-primary mt-1">NIM: {{ $student->nim }}</span>
                </div>
                <div class="col-md-5 border-end">
                    <p class="mb-1"><strong>Program Studi:</strong> {{ $student->prodi }}</p>
                    <p class="mb-1"><strong>Semester:</strong> Semester {{ $student->semester }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $student->user?->email }}</p>
                    <p class="mb-0"><strong>Status Judul Skripsi:</strong> 
                        @if($thesis)
                            <span class="badge bg-info">{{ strtoupper(str_replace('_', ' ', $thesis->status)) }}</span>
                        @else
                            <span class="badge bg-secondary">Belum Mengajukan Judul</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-5">
                    <h6 class="font-w600 text-dark mb-2"><i class="la la-check-circle me-1"></i> Syarat Clearance 3 Pihak:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>1. Keuangan / UKT:</span>
                        @if($student->is_paid)
                            <span class="badge bg-success"><i class="la la-check"></i> Lunas (Clear)</span>
                        @else
                            <span class="badge bg-danger"><i class="la la-times"></i> Belum Lunas</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>2. Bebas Perpustakaan:</span>
                        @if($student->is_library_clear)
                            <span class="badge bg-success"><i class="la la-check"></i> Bebas Pustaka</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="la la-times"></i> Pending Perpus</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>3. Kelayakan Akademik (BAAK):</span>
                        @if($student->is_coursework_completed)
                            <span class="badge bg-success"><i class="la la-check"></i> Memenuhi SKS</span>
                        @else
                            <span class="badge bg-danger"><i class="la la-times"></i> Belum Memenuhi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Thesis Progression Step Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 text-dark font-w600"><i class="la la-road me-1"></i> Progress Alur Skripsi Mahasiswa</h5>
        </div>
        
        <!-- Step 1: Proposal -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 {{ ($thesis?->latestProposal?->is_baak_approved && $thesis?->latestProposal?->is_finance_approved && $thesis?->latestProposal?->is_kaprodi_approved) ? 'border-start border-4 border-success' : ($thesis ? 'border-start border-4 border-warning' : 'border-start border-4 border-secondary') }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle {{ ($thesis?->latestProposal?->is_baak_approved && $thesis?->latestProposal?->is_finance_approved && $thesis?->latestProposal?->is_kaprodi_approved) ? 'bg-success' : ($thesis ? 'bg-warning' : 'bg-secondary') }} text-white px-3 py-2 me-2 font-w700">1</div>
                        <h6 class="mb-0 font-w600">Proposal & Seminar</h6>
                    </div>
                    @if($thesis)
                        <p class="small text-muted mb-2">Judul: {{ Str::limit($thesis->title, 45) }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            @if($thesis->latestProposal && $thesis->latestProposal->is_baak_approved && $thesis->latestProposal->is_finance_approved && $thesis->latestProposal->is_kaprodi_approved)
                                <span class="badge bg-success-soft text-success"><i class="la la-check"></i> Siap Seminar</span>
                            @elseif($thesis->latestProposal && ($thesis->latestProposal->is_baak_approved || $thesis->latestProposal->is_finance_approved || $thesis->latestProposal->is_kaprodi_approved))
                                <span class="badge bg-info-soft text-info"><i class="la la-clock"></i> Validasi Sebagian</span>
                            @else
                                <span class="badge bg-warning-soft text-warning"><i class="la la-hourglass-half"></i> Menunggu Validasi</span>
                            @endif
                            <a href="{{ route('student.proposal.index') }}" class="btn btn-xs btn-outline-primary">Lihat Detail</a>
                        </div>
                    @else
                        <p class="small text-muted mb-2">Belum mengunggah draf proposal.</p>
                        <a href="{{ route('student.proposal.index') }}" class="btn btn-xs btn-outline-primary">Upload Proposal</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Step 2: Bimbingan -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 {{ $approvedLogsCount >= 10 ? 'border-start border-4 border-success' : ($approvedLogsCount > 0 ? 'border-start border-4 border-warning' : 'border-start border-4 border-secondary') }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle {{ $approvedLogsCount >= 10 ? 'bg-success' : 'bg-warning' }} text-white px-3 py-2 me-2 font-w700">2</div>
                        <h6 class="mb-0 font-w600">Bimbingan Skripsi</h6>
                    </div>
                    <p class="small text-muted mb-2">Total Log Disetujui: <strong>{{ $approvedLogsCount }} / 10 Min.</strong></p>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ min(100, ($approvedLogsCount/10)*100) }}%"></div>
                    </div>
                    <a href="{{ route('student.mentoring-logs.index') }}" class="btn btn-xs btn-outline-warning">Input Bimbingan</a>
                </div>
            </div>
        </div>

        <!-- Step 3: Sidang & Revisi -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 {{ $thesis?->thesisDefenses->last()?->status == 'passed' ? 'border-start border-4 border-success' : 'border-start border-4 border-secondary' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-info text-white px-3 py-2 me-2 font-w700">3</div>
                        <h6 class="mb-0 font-w600">Sidang Skripsi</h6>
                    </div>
                    @if($thesis?->thesisDefenses->last())
                        @php $def = $thesis->thesisDefenses->last(); @endphp
                        <p class="small text-muted mb-1">Status: <strong>{{ ucfirst($def->status) }}</strong></p>
                        <p class="small text-muted mb-2">Nilai: <span class="badge bg-primary">{{ $def->grade ?? 'Pending' }}</span></p>
                        <a href="{{ route('student.revisions.index') }}" class="btn btn-xs btn-outline-info">Cek Revisi</a>
                    @else
                        <p class="small text-muted mb-2">Belum mendaftar sidang skripsi.</p>
                        <a href="{{ route('student.defenses.index') }}" class="btn btn-xs btn-outline-secondary">Daftar Sidang</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Step 4: Yudisium -->
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 {{ $student->yudisiums->last() ? 'border-start border-4 border-success' : 'border-start border-4 border-secondary' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-primary text-white px-3 py-2 me-2 font-w700">4</div>
                        <h6 class="mb-0 font-w600">Yudisium & SK</h6>
                    </div>
                    @if($student->yudisiums->last())
                        @php $yud = $student->yudisiums->last(); @endphp
                        <p class="small text-muted mb-1">No SK: {{ $yud->sk_number }}</p>
                        <a href="{{ route('yudisiums.print', $yud->id) }}" target="_blank" class="btn btn-xs btn-success"><i class="la la-print"></i> Cetak SK</a>
                    @else
                        <p class="small text-muted mb-2">SK Yudisium belum diterbitkan.</p>
                        <span class="badge bg-secondary">Menunggu Sidang</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Details Sections -->
    <div class="row">
        <!-- Dosen Pembimbing -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title text-primary mb-0"><i class="la la-chalkboard-teacher me-1"></i> Tim Dosen Pembimbing</h5>
                </div>
                <div class="card-body">
                    @if($thesis && $thesis->thesisAdvisors->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($thesis->thesisAdvisors as $adv)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-0 font-w600">{{ $adv->lecturer?->user?->name }}</h6>
                                    <small class="text-muted">NIDN: {{ $adv->lecturer?->nidn }} | {{ ucfirst($adv->type) }} Advisor</small>
                                </div>
                                <span class="badge {{ $adv->is_approved_for_defense ? 'bg-success' : 'bg-warning' }}">
                                    {{ $adv->is_approved_for_defense ? 'Siap Sidang' : 'Bimbingan Aktif' }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Belum ada dosen pembimbing yang ditetapkan.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Log Bimbingan Terakhir -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="la la-history me-1"></i> Log Bimbingan Terbaru</h5>
                    <a href="{{ route('student.mentoring-logs.index') }}" class="btn btn-xs btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if($thesis && $thesis->mentoringLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Catatan Mahasiswa</th>
                                        <th>Status Approval</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($thesis->mentoringLogs->take(5) as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->mentoring_date)->format('d M Y') }}</td>
                                        <td>{{ Str::limit($log->notes, 40) }}</td>
                                        <td>
                                            @if($log->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($log->status == 'rejected')
                                                <span class="badge bg-danger">Revisi</span>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted p-3 mb-0">Belum ada catatan log bimbingan yang dimasukkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
