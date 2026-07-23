@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Revisi & Komentar Seminar Proposal</h4>
            <p class="mb-0">Kelola daftar revisi, saran, dan komentar dari penguji seminar proposal</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Komentar
        </button>
    </div>
</div>





<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Revisi & Komentar Seminar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SKRIPSI (MAHASISWA)</strong></th>
                                <th><strong>DOSEN PENGUJI</strong></th>
                                <th><strong>KOMENTAR / REVISI</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $index => $commentObj)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($commentObj->proposalSeminar->thesis->title ?? '-', 45) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $commentObj->proposalSeminar->thesis->student->user->name ?? '-' }}</small>
                                </td>
                                <td>{{ $commentObj->lecturer->user->name ?? '-' }}</td>
                                <td><span class="text-wrap d-block" style="max-width:450px;">{{ $commentObj->comment }}</span></td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $commentObj->id }}" 
                                                data-proposal_seminar_id="{{ $commentObj->proposal_seminar_id }}" 
                                                data-lecturer_id="{{ $commentObj->lecturer_id }}"
                                                data-comment="{{ $commentObj->comment }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('proposal-comments.destroy', $commentObj->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus komentar ini?">
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
                                <td colspan="5" class="text-center">Belum ada data komentar seminar.</td>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Komentar Seminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('proposal-comments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Jadwal Seminar Proposal</label>
                        <select name="proposal_seminar_id" class="form-control" required>
                            <option value="">-- Pilih Jadwal Seminar --</option>
                            @foreach($seminars as $seminar)
                            <option value="{{ $seminar->id }}">Mhs: {{ $seminar->thesis->student->user->name ?? '' }} - {{ $seminar->seminar_date ? $seminar->seminar_date->format('d M Y') : '' }} ({{ Str::limit($seminar->thesis->title, 25) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Dosen Penguji</label>
                        <select name="lecturer_id" class="form-control" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">{{ $lecturer->user->name ?? '' }} (NIDN: {{ $lecturer->nidn }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Komentar / Detail Revisi</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Masukkan detail revisi atau komentar masukan" required></textarea>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Komentar Seminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Jadwal Seminar Proposal</label>
                        <select name="proposal_seminar_id" id="edit_proposal_seminar_id" class="form-control" required>
                            @foreach($seminars as $seminar)
                            <option value="{{ $seminar->id }}">Mhs: {{ $seminar->thesis->student->user->name ?? '' }} - {{ $seminar->seminar_date ? $seminar->seminar_date->format('d M Y') : '' }} ({{ Str::limit($seminar->thesis->title, 25) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Dosen Penguji</label>
                        <select name="lecturer_id" id="edit_lecturer_id" class="form-control" required>
                            @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">{{ $lecturer->user->name ?? '' }} (NIDN: {{ $lecturer->nidn }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Komentar / Detail Revisi</label>
                        <textarea name="comment" id="edit_comment" class="form-control" rows="4" required></textarea>
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
                const seminarId = this.getAttribute('data-proposal_seminar_id');
                const lecturerId = this.getAttribute('data-lecturer_id');
                const comment = this.getAttribute('data-comment');
                
                document.getElementById('editForm').action = `/admin/proposal-comments/${id}`;
                document.getElementById('edit_proposal_seminar_id').value = seminarId;
                document.getElementById('edit_lecturer_id').value = lecturerId;
                document.getElementById('edit_comment').value = comment;
            });
        });
    });
</script>
@endsection
