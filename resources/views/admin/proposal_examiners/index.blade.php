@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Penguji Seminar Proposal</h4>
            <p class="mb-0">Kelola ketua penguji (chairman) dan anggota penguji (member) untuk seminar proposal mahasiswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Penguji
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
                <h4 class="card-title">Daftar Penguji Seminar Proposal</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SEMINAR PROPOSAL (MAHASISWA)</strong></th>
                                <th><strong>DOSEN PENGUJI</strong></th>
                                <th><strong>JABATAN PENGUJI</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examiners as $index => $examiner)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($examiner->proposalSeminar->thesis->title ?? '-', 45) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $examiner->proposalSeminar->thesis->student->user->name ?? '-' }} | Tgl: {{ $examiner->proposalSeminar->seminar_date ? $examiner->proposalSeminar->seminar_date->format('d M Y') : '-' }}</small>
                                </td>
                                <td>{{ $examiner->lecturer->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $examiner->position === 'chairman' ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $examiner->position === 'chairman' ? 'Ketua Penguji' : 'Anggota Penguji' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $examiner->id }}" 
                                                data-proposal_seminar_id="{{ $examiner->proposal_seminar_id }}" 
                                                data-lecturer_id="{{ $examiner->lecturer_id }}"
                                                data-position="{{ $examiner->position }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('proposal-examiners.destroy', $examiner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penguji seminar ini?')">
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
                                <td colspan="5" class="text-center">Belum ada data penguji seminar.</td>
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
                <h5 class="modal-title">Tambah Penguji Seminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('proposal-examiners.store') }}" method="POST">
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
                        <label class="form-label">Jabatan Penguji</label>
                        <select name="position" class="form-control" required>
                            <option value="chairman">Ketua Penguji (Chairman)</option>
                            <option value="member">Anggota Penguji (Member)</option>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Penguji Seminar</h5>
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
                        <label class="form-label">Jabatan Penguji</label>
                        <select name="position" id="edit_position" class="form-control" required>
                            <option value="chairman">Ketua Penguji (Chairman)</option>
                            <option value="member">Anggota Penguji (Member)</option>
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
                const seminarId = this.getAttribute('data-proposal_seminar_id');
                const lecturerId = this.getAttribute('data-lecturer_id');
                const position = this.getAttribute('data-position');
                
                document.getElementById('editForm').action = `/admin/proposal-examiners/${id}`;
                document.getElementById('edit_proposal_seminar_id').value = seminarId;
                document.getElementById('edit_lecturer_id').value = lecturerId;
                document.getElementById('edit_position').value = position;
            });
        });
    });
</script>
@endsection
