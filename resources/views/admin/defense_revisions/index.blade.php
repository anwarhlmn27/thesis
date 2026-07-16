@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Revisi Sidang Skripsi</h4>
            <p class="mb-0">Kelola catatan revisi dari penguji sidang skripsi beserta status persetujuan</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Revisi Sidang
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
                <h4 class="card-title">Daftar Revisi Sidang Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SIDANG SKRIPSI (MHS)</strong></th>
                                <th><strong>DOSEN PENGUJI</strong></th>
                                <th><strong>DESKRIPSI REVISI</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revisions as $index => $revision)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($revision->thesisDefense->thesis->title ?? '-', 45) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $revision->thesisDefense->thesis->student->user->name ?? '-' }}</small>
                                </td>
                                <td>{{ $revision->lecturer->user->name ?? '-' }}</td>
                                <td><span class="text-wrap d-block" style="max-width:400px;">{{ $revision->description }}</span></td>
                                <td>
                                    @if($revision->is_approved)
                                    <span class="badge badge-success">Approved ({{ $revision->approved_at ? $revision->approved_at->format('d M Y') : '-' }})</span>
                                    @else
                                    <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $revision->id }}" 
                                                data-thesis_defense_id="{{ $revision->thesis_defense_id }}" 
                                                data-lecturer_id="{{ $revision->lecturer_id }}"
                                                data-description="{{ $revision->description }}"
                                                data-is_approved="{{ $revision->is_approved ? '1' : '0' }}"
                                                data-approved_at="{{ $revision->approved_at ? $revision->approved_at->format('Y-m-d') : '' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('defense-revisions.destroy', $revision->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus revisi sidang ini?')">
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
                                <td colspan="6" class="text-center">Belum ada data revisi sidang.</td>
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
                <h5 class="modal-title">Tambah Catatan Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('defense-revisions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Sidang Skripsi Mahasiswa</label>
                        <select name="thesis_defense_id" class="form-control" required>
                            <option value="">-- Pilih Jadwal Sidang --</option>
                            @foreach($defenses as $defense)
                            <option value="{{ $defense->id }}">Mhs: {{ $defense->thesis->student->user->name ?? '' }} - {{ $defense->defense_date ? $defense->defense_date->format('d M Y') : '' }} ({{ Str::limit($defense->thesis->title, 25) }})</option>
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
                        <label class="form-label">Deskripsi Revisi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Masukkan detail catatan revisi dari penguji" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_approved" class="form-check-input" id="add_is_approved" value="1">
                            <label class="form-check-label" for="add_is_approved">Tandai sudah disetujui (Approved)</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal Persetujuan</label>
                        <input type="date" name="approved_at" class="form-control">
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
                <h5 class="modal-title">Edit Catatan Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Sidang Skripsi Mahasiswa</label>
                        <select name="thesis_defense_id" id="edit_thesis_defense_id" class="form-control" required>
                            @foreach($defenses as $defense)
                            <option value="{{ $defense->id }}">Mhs: {{ $defense->thesis->student->user->name ?? '' }} - {{ $defense->defense_date ? $defense->defense_date->format('d M Y') : '' }} ({{ Str::limit($defense->thesis->title, 25) }})</option>
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
                        <label class="form-label">Deskripsi Revisi</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_approved" class="form-check-input" id="edit_is_approved" value="1">
                            <label class="form-check-label" for="edit_is_approved">Tandai sudah disetujui (Approved)</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal Persetujuan</label>
                        <input type="date" name="approved_at" id="edit_approved_at" class="form-control">
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
                const defenseId = this.getAttribute('data-thesis_defense_id');
                const lecturerId = this.getAttribute('data-lecturer_id');
                const description = this.getAttribute('data-description');
                const isApproved = this.getAttribute('data-is_approved');
                const approvedAt = this.getAttribute('data-approved_at');
                
                document.getElementById('editForm').action = `/admin/defense-revisions/${id}`;
                document.getElementById('edit_thesis_defense_id').value = defenseId;
                document.getElementById('edit_lecturer_id').value = lecturerId;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_approved_at').value = approvedAt || '';
                
                document.getElementById('edit_is_approved').checked = isApproved === '1';
            });
        });
    });
</script>
@endsection
