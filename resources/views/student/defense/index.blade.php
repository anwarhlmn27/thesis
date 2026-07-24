@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-gavel mr-2 text-white"></i>Daftar Sidang Skripsi</h4>
                <p class="mb-0 text-white-50">Cek kelayakan dan daftar sidang skripsi Anda</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Cek Syarat Sidang</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Log Bimbingan (Min. 10)
                            @if($approvedLogs >= 10)
                                <span class="badge badge-success badge-pill"><i class="fa fa-check"></i> {{ $approvedLogs }} Log</span>
                            @else
                                <span class="badge badge-danger badge-pill">{{ $approvedLogs }} Log</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bebas Keuangan (UKT)
                            @if(auth()->user()->student->is_paid)
                                <span class="badge badge-success badge-pill"><i class="fa fa-check"></i> Lunas</span>
                            @else
                                <span class="badge badge-danger badge-pill">Belum</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bebas Perpustakaan
                            @if(auth()->user()->student->is_library_clear)
                                <span class="badge badge-success badge-pill"><i class="fa fa-check"></i> Bebas</span>
                            @else
                                <span class="badge badge-danger badge-pill">Belum</span>
                            @endif
                        </li>
                    </ul>

                    @if($defense)
                        <div class="alert alert-info">Anda sudah terdaftar untuk Sidang Skripsi.</div>
                    @elseif($eligibleForDefense)
                        <form action="{{ route('student.defenses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">File Skripsi Final (PDF, Max 10MB)</label>
                                <input type="file" name="final_file" class="form-control" accept=".pdf" required>
                                <small class="text-muted">Pastikan file sudah disetujui oleh dosen pembimbing.</small>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Apakah Anda yakin ingin mendaftar sidang?')">Daftar Sidang Skripsi</button>
                        </form>
                    @else
                        <div class="alert alert-warning">Persyaratan pendaftaran sidang belum terpenuhi. Silakan penuhi syarat di atas terlebih dahulu.</div>
                        <button class="btn btn-secondary w-100" disabled>Daftar Sidang Skripsi</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Jadwal Sidang Skripsi</h5>
                </div>
                <div class="card-body">
                    @if($defense)
                        @if($defense->status == 'pending')
                            <div class="alert alert-warning">Pendaftaran sedang diproses oleh BAAK. Menunggu penetapan jadwal.</div>
                        @else
                            <div class="alert alert-info">
                                <h5><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($defense->scheduled_at)->format('d F Y, H:i') }} WIB</h5>
                                <p class="mb-0"><i class="fa fa-map-marker"></i> Ruangan: <strong>{{ $defense->room }}</strong></p>
                            </div>
                            
                            @if($defense->status == 'completed')
                                <div class="alert alert-success mt-3"><i class="fa fa-check-circle"></i> Sidang telah selesai dilaksanakan.</div>
                            @endif

                            <h6 class="mt-4">Tim Penguji:</h6>
                            <ul>
                                @foreach($defense->defenseExaminers as $examiner)
                                    <li>{{ $examiner->lecturer->user->name }} ({{ ucfirst($examiner->position) }})</li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        <div class="alert alert-secondary text-center">Belum ada data pendaftaran sidang.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
