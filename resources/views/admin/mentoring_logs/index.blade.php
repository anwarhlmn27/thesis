@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Log Bimbingan</h4>
            <p class="mb-0">Kelola riwayat bimbingan mahasiswa dan umpan balik pembimbing</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Log Bimbingan
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
    <strong>Sukses!</strong> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
    <strong>Error!</strong> Mohon periksa form kembali.
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Log Bimbingan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SKRIPSI (MHS)</strong></th>
                                <th><strong>PEMBIMBING</strong></th>
                                <th><strong>TANGGAL</strong></th>
                                <th><strong>CATATAN BIMBINGAN</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $index => $log)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($log->thesis->title ?? '-', 35) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $log->thesis->student->user->name ?? '-' }}</small>
                                </td>
                                <td>{{ $log->thesisAdvisor->lecturer->user->name ?? '-' }}</td>
                                <td>{{ $log->mentoring_date ? $log->mentoring_date->format('d M Y') : '-' }}</td>
                                <td>
                                    <strong>Notes:</strong> {{ Str::limit($log->notes, 60) }}
                                    @if($log->feedback)
                                    <br><small class="text-info"><strong>Feedback:</strong> {{ Str::limit($log->feedback, 60) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $log->status === 'approved' ? 'badge-success' : ($log->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $log->id }}" 
                                                data-thesis_id="{{ $log->thesis_id }}" 
                                                data-thesis_advisor_id="{{ $log->thesis_advisor_id }}"
                                                data-mentoring_date="{{ $log->mentoring_date ? $log->mentoring_date->format('Y-m-d') : '' }}"
                                                data-notes="{{ $log->notes }}"
                                                data-document_path="{{ $log->document_path }}"
                                                data-status="{{ $log->status }}"
                                                data-feedback="{{ $log->feedback }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('mentoring-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log bimbingan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data log bimbingan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Log Bimbingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mentoring-logs.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Skripsi Mahasiswa</label>
                            <select name="thesis_id" class="form-control" required>
                                <option value="">-- Pilih Skripsi --</option>
                                @foreach($theses as $thesis)
                                <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 40) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembimbing Skripsi</label>
                            <select name="thesis_advisor_id" class="form-control" required>
                                <option value="">-- Pilih Pembimbing --</option>
                                @foreach($advisors as $advisor)
                                <option value="{{ $advisor->id }}">{{ $advisor->lecturer->user->name ?? '' }} ({{ $advisor->type }}) - {{ Str::limit($advisor->thesis->title ?? '', 20) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bimbingan</label>
                            <input type="date" name="mentoring_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Path Dokumen Bimbingan</label>
                            <input type="text" name="document_path" class="form-control" placeholder="uploads/bimbingan_doc.pdf">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Catatan Aktivitas Bimbingan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan bab atau bahasan bimbingan" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Verifikasi</label>
                        <select name="status" class="form-control" required>
                            <option value="submitted">Submitted</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Umpan Balik (Feedback)</label>
                        <textarea name="feedback" class="form-control" rows="2" placeholder="Umpan balik dari pembimbing (jika ada)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Log Bimbingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Skripsi Mahasiswa</label>
                            <select name="thesis_id" id="edit_thesis_id" class="form-control" required>
                                @foreach($theses as $thesis)
                                <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 40) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembimbing Skripsi</label>
                            <select name="thesis_advisor_id" id="edit_thesis_advisor_id" class="form-control" required>
                                @foreach($advisors as $advisor)
                                <option value="{{ $advisor->id }}">{{ $advisor->lecturer->user->name ?? '' }} ({{ $advisor->type }}) - {{ Str::limit($advisor->thesis->title ?? '', 20) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bimbingan</label>
                            <input type="date" name="mentoring_date" id="edit_mentoring_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Path Dokumen Bimbingan</label>
                            <input type="text" name="document_path" id="edit_document_path" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Catatan Aktivitas Bimbingan</label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Verifikasi</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="submitted">Submitted</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Umpan Balik (Feedback)</label>
                        <textarea name="feedback" id="edit_feedback" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editBtns = document.querySelectorAll('.edit-btn');
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const thesisId = this.getAttribute('data-thesis_id');
                const advisorId = this.getAttribute('data-thesis_advisor_id');
                const date = this.getAttribute('data-mentoring_date');
                const notes = this.getAttribute('data-notes');
                const documentPath = this.getAttribute('data-document_path');
                const status = this.getAttribute('data-status');
                const feedback = this.getAttribute('data-feedback');
                
                document.getElementById('editForm').action = `/admin/mentoring-logs/${id}`;
                document.getElementById('edit_thesis_id').value = thesisId;
                document.getElementById('edit_thesis_advisor_id').value = advisorId;
                document.getElementById('edit_mentoring_date').value = date;
                document.getElementById('edit_notes').value = notes;
                document.getElementById('edit_document_path').value = documentPath || '';
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_feedback').value = feedback || '';
            });
        });
    });
</script>
@endsection
