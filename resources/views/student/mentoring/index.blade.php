@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-book mr-2 text-white"></i>Bimbingan Skripsi</h4>
                <p class="mb-0 text-white-50">Catat dan pantau log bimbingan skripsi Anda</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Input Log Bimbingan</h5>
                </div>
                <div class="card-body">
                    @if(!$thesis || $advisors->isEmpty())
                        <div class="alert alert-warning">Anda belum memiliki skripsi aktif atau dosen pembimbing belum ditetapkan.</div>
                    @else
                        <form action="{{ route('student.mentoring-logs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="thesis_id" value="{{ $thesis->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Dosen Pembimbing</label>
                                <select name="thesis_advisor_id" class="form-select" required>
                                    <option value="">-- Pilih Pembimbing --</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{ $advisor->id }}">{{ $advisor->lecturer->user->name }} ({{ $advisor->position == 'supervisor_1' ? 'Pembimbing 1' : 'Pembimbing 2' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Bimbingan</label>
                                <input type="date" name="mentoring_date" class="form-control" required max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Materi / Catatan Bimbingan</label>
                                <textarea name="notes" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File Lampiran (Opsional, PDF max 10MB)</label>
                                <input type="file" name="document_file" class="form-control" accept=".pdf">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim Log Bimbingan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Riwayat Bimbingan</h5>
                    <span class="badge badge-primary">Total: {{ count($logs) }} Log</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pembimbing</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->mentoring_date)->format('d M Y') }}</td>
                                        <td>{{ $log->thesisAdvisor->lecturer->user->name }}</td>
                                        <td>
                                            {{ $log->notes }}
                                            @if($log->document_path)
                                                <br><a href="{{ Storage::url($log->document_path) }}" target="_blank" class="badge badge-info mt-1"><i class="fa fa-download"></i> Lampiran</a>
                                            @endif
                                            
                                            @if($log->feedback)
                                                <div class="mt-2 p-2 bg-light rounded border border-warning">
                                                    <small class="text-warning fw-bold">Feedback Dosen:</small><br>
                                                    <small>{{ $log->feedback }}</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status == 'submitted')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($log->status == 'approved')
                                                <span class="badge badge-success">Disetujui</span>
                                            @elseif($log->status == 'rejected')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada riwayat bimbingan.</td>
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
