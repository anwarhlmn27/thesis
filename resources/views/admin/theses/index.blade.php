@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Daftar Skripsi</h4>
            <p class="mb-0">Kelola judul skripsi mahasiswa dan status tahapan skripsi</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Skripsi
        </button>
    </div>
</div>





<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>MAHASISWA</strong></th>
                                <th><strong>JUDUL SKRIPSI</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($theses as $index => $thesis)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    {{ $thesis->student->user->name ?? '-' }}<br>
                                    <small class="text-muted">NIM: {{ $thesis->student->nim ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $thesis->title }}</strong>
                                    @if($thesis->abstract)
                                    <br><small class="text-muted text-wrap d-block" style="max-width:400px;">{{ Str::limit($thesis->abstract, 100) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info text-capitalize">
                                        {{ str_replace('_', ' ', $thesis->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $thesis->id }}" 
                                                data-student_id="{{ $thesis->student_id }}" 
                                                data-title="{{ $thesis->title }}"
                                                data-abstract="{{ $thesis->abstract }}"
                                                data-proposal_file_path="{{ $thesis->proposal_file_path }}"
                                                data-final_file_path="{{ $thesis->final_file_path }}"
                                                data-signed_revision_proof_path="{{ $thesis->signed_revision_proof_path }}"
                                                data-status="{{ $thesis->status }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('theses.destroy', $thesis->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus skripsi ini?">
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
                                <td colspan="5" class="text-center">Belum ada data skripsi.</td>
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
                <h5 class="modal-title">Tambah Skripsi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('theses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->nim }} - {{ $student->user->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Judul Skripsi</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul skripsi lengkap" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Abstrak</label>
                        <textarea name="abstract" class="form-control" rows="4" placeholder="Masukkan abstrak skripsi (opsional)"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Path File Proposal</label>
                            <input type="text" name="proposal_file_path" class="form-control" placeholder="uploads/proposal.pdf">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Path File Laporan Akhir</label>
                            <input type="text" name="final_file_path" class="form-control" placeholder="uploads/final.pdf">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Path Lembar Revisi</label>
                            <input type="text" name="signed_revision_proof_path" class="form-control" placeholder="uploads/revision_proof.pdf">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Skripsi</label>
                        <select name="status" class="form-control" required>
                            <option value="proposal_submitted">Proposal Submitted</option>
                            <option value="proposal_seminar_scheduled">Proposal Seminar Scheduled</option>
                            <option value="proposal_seminar_done">Proposal Seminar Done</option>
                            <option value="advisor_assigned">Advisor Assigned</option>
                            <option value="mentoring">Mentoring</option>
                            <option value="defense_registered">Defense Registered</option>
                            <option value="defense_scheduled">Defense Scheduled</option>
                            <option value="defense_done">Defense Done</option>
                            <option value="revision_period">Revision Period</option>
                            <option value="revision_approved">Revision Approved</option>
                            <option value="yudisium_ready">Yudisium Ready</option>
                            <option value="graduated">Graduated</option>
                        </select>
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
                <h5 class="modal-title">Edit Data Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <select name="student_id" id="edit_student_id" class="form-control" required>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->nim }} - {{ $student->user->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Judul Skripsi</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Abstrak</label>
                        <textarea name="abstract" id="edit_abstract" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Path File Proposal</label>
                            <input type="text" name="proposal_file_path" id="edit_proposal_file_path" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Path File Laporan Akhir</label>
                            <input type="text" name="final_file_path" id="edit_final_file_path" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Path Lembar Revisi</label>
                            <input type="text" name="signed_revision_proof_path" id="edit_signed_revision_proof_path" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Skripsi</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="proposal_submitted">Proposal Submitted</option>
                            <option value="proposal_seminar_scheduled">Proposal Seminar Scheduled</option>
                            <option value="proposal_seminar_done">Proposal Seminar Done</option>
                            <option value="advisor_assigned">Advisor Assigned</option>
                            <option value="mentoring">Mentoring</option>
                            <option value="defense_registered">Defense Registered</option>
                            <option value="defense_scheduled">Defense Scheduled</option>
                            <option value="defense_done">Defense Done</option>
                            <option value="revision_period">Revision Period</option>
                            <option value="revision_approved">Revision Approved</option>
                            <option value="yudisium_ready">Yudisium Ready</option>
                            <option value="graduated">Graduated</option>
                        </select>
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
                const studentId = this.getAttribute('data-student_id');
                const title = this.getAttribute('data-title');
                const abstract = this.getAttribute('data-abstract');
                const proposalFile = this.getAttribute('data-proposal_file_path');
                const finalFile = this.getAttribute('data-final_file_path');
                const revisionProof = this.getAttribute('data-signed_revision_proof_path');
                const status = this.getAttribute('data-status');
                
                document.getElementById('editForm').action = `/admin/theses/${id}`;
                document.getElementById('edit_student_id').value = studentId;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_abstract').value = abstract || '';
                document.getElementById('edit_proposal_file_path').value = proposalFile || '';
                document.getElementById('edit_final_file_path').value = finalFile || '';
                document.getElementById('edit_signed_revision_proof_path').value = revisionProof || '';
                document.getElementById('edit_status').value = status;
            });
        });
    });
</script>
@endsection
