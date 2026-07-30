@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-book mr-2 text-white"></i>Persetujuan Log Bimbingan</h4>
                <p class="mb-0 text-white-50">Review dan berikan persetujuan untuk log bimbingan mahasiswa</p>
            </div>
        </div>
    </div>



    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Mahasiswa</th>
                            <th>Catatan Bimbingan</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->mentoring_date)->format('d M Y') }}</td>
                                <td>
                                    <strong>{{ $log->thesis->student->user->name }}</strong><br>
                                    <small class="text-muted">NIM: {{ $log->thesis->student->nim }}</small>
                                </td>
                                <td>{{ $log->notes }}</td>
                                <td>
                                    @if($log->document_path)
                                        <a href="{{ Storage::url($log->document_path) }}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-download"></i> Unduh</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($log->status == 'submitted')
                                        <span class="badge badge-warning">Menunggu Persetujuan</span>
                                    @elseif($log->status == 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($log->status == 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $log->id }}">
                                        Review
                                    </button>

                                    <!-- Modal Review Log -->
                                    <div class="modal fade" id="reviewModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('dosen.mentoring-logs.update', $log->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Review Log Bimbingan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Feedback / Catatan Dosen (Opsional)</label>
                                                            <textarea name="feedback" class="form-control" rows="3">{{ $log->feedback }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Keputusan</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="">-- Pilih --</option>
                                                                <option value="approved" {{ $log->status == 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                                                <option value="rejected" {{ $log->status == 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada log bimbingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
