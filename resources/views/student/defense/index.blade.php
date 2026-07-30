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
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Log Bimbingan (Min. 10)
                            @if($approvedLogs >= 10)
                                <span class="badge badge-success badge-pill"><i class="fa fa-check"></i> {{ $approvedLogs }} Log</span>
                            @else
                                <span class="badge badge-warning badge-pill">{{ $approvedLogs }} Log</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Persetujuan Pembimbing (ACC)
                            @if($isAdvisorApproved)
                                <span class="badge badge-success badge-pill"><i class="fa fa-check"></i> ACC</span>
                            @else
                                <span class="badge badge-danger badge-pill">Belum ACC</span>
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
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bebas Akademik (BAAK)
                            @if(auth()->user()->student->is_coursework_completed)
                                <span class="badge badge-success badge-pill"><i class="fa fa-check"></i> Disetujui</span>
                            @else
                                <span class="badge badge-danger badge-pill">Belum</span>
                            @endif
                        </li>
                    </ul>

                    @if($defense && ($defense->status != 'registered' || $defense->defense_date))
                        <div class="alert alert-info">Anda sudah terdaftar untuk Sidang Skripsi. Pendaftaran Anda sudah dijadwalkan.</div>
                    @elseif($defense && $defense->status == 'registered')
                        <div class="alert alert-warning mb-3">Pendaftaran Anda sedang menunggu jadwal dari BAAK. Anda masih dapat mengedit data di bawah ini.</div>
                        <form action="{{ route('student.defenses.update') }}" method="POST" enctype="multipart/form-data" onsubmit="confirmAction(event, this)" data-confirm-message="Apakah Anda yakin ingin menyimpan perubahan pendaftaran?" data-confirm-btn="Ya, Simpan!">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Judul Skripsi Final</label>
                                <textarea name="title" class="form-control" rows="3" required>{{ $thesis ? $thesis->title : '' }}</textarea>
                                <small class="text-muted">Sesuaikan judul jika ada perubahan/revisi sejak seminar proposal.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Update File Skripsi Final (PDF, Max 10MB)</label>
                                <input type="file" name="final_file" class="form-control" accept=".pdf">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah file. <a href="{{ asset('storage/'.$thesis->final_file_path) }}" target="_blank">Lihat File Saat Ini</a></small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning flex-grow-1">Simpan Perubahan</button>
                            </div>
                        </form>
                        <form action="{{ route('student.defenses.destroy') }}" method="POST" class="mt-2" onsubmit="confirmAction(event, this)" data-confirm-message="Apakah Anda yakin ingin membatalkan pendaftaran sidang ini? Anda harus mendaftar ulang jika ingin sidang." data-confirm-btn="Ya, Batalkan!">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">Batalkan Pendaftaran</button>
                        </form>
                    @elseif($eligibleForDefense)
                        <form action="{{ route('student.defenses.store') }}" method="POST" enctype="multipart/form-data" onsubmit="confirmAction(event, this)" data-confirm-message="Apakah Anda yakin ingin mendaftar sidang?" data-confirm-btn="Ya, Daftar!">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Judul Skripsi Final</label>
                                <textarea name="title" class="form-control" rows="3" required>{{ $thesis ? $thesis->title : '' }}</textarea>
                                <small class="text-muted">Sesuaikan judul jika ada perubahan/revisi sejak seminar proposal.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File Skripsi Final (PDF, Max 10MB)</label>
                                <input type="file" name="final_file" class="form-control" accept=".pdf" required>
                                <small class="text-muted">Pastikan file sudah disetujui oleh dosen pembimbing.</small>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Daftar Sidang Skripsi</button>
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
                        @if(!$defense->defense_date && $defense->status == 'registered')
                            <div class="alert alert-warning">Pendaftaran sedang diproses oleh BAAK. Menunggu penetapan jadwal.</div>
                        @else
                            <div class="alert alert-info">
                                <h5><i class="fa fa-calendar"></i> {{ $defense->defense_date ? \Carbon\Carbon::parse($defense->defense_date)->format('d F Y, H:i') : 'Belum ditentukan' }} WIB</h5>
                                <p class="mb-0"><i class="fa fa-map-marker"></i> Ruangan: <strong>{{ $defense->room ?? '-' }}</strong></p>
                            </div>
                            
                            @if($defense->status == 'passed')
                                <div class="alert alert-success mt-3"><i class="fa fa-check-circle"></i> Sidang telah selesai dilaksanakan (Lulus).</div>
                            @elseif($defense->status == 'failed')
                                <div class="alert alert-danger mt-3"><i class="fa fa-times-circle"></i> Sidang telah selesai dilaksanakan (Tidak Lulus).</div>
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
