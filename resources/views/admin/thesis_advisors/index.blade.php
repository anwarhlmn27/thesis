@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Pembimbing Skripsi</h4>
            <p class="mb-0">Kelola dosen pembimbing utama (primary) dan pendamping (secondary) untuk skripsi mahasiswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Pembimbing
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
                <h4 class="card-title">Daftar Pembimbing Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SKRIPSI (MAHASISWA)</strong></th>
                                <th><strong>DOSEN PEMBIMBING</strong></th>
                                <th><strong>TIPE</strong></th>
                                <th><strong>PERSETUJUAN SIDANG</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($advisors as $index => $advisor)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($advisor->thesis->title ?? '-', 50) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $advisor->thesis->student->user->name ?? '-' }} (NIM: {{ $advisor->thesis->student->nim ?? '-' }})</small>
                                </td>
                                <td>{{ $advisor->lecturer->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $advisor->type === 'primary' ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $advisor->type === 'primary' ? 'Utama' : 'Pendamping' }}
                                    </span>
                                </td>
                                <td>
                                    @if($advisor->is_approved_for_defense)
                                    <span class="badge badge-success">Approved ({{ $advisor->approved_at ? $advisor->approved_at->format('d M Y') : '-' }})</span>
                                    @else
                                    <span class="badge badge-warning">Belum Disetujui</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $advisor->id }}" 
                                                data-thesis_id="{{ $advisor->thesis_id }}" 
                                                data-lecturer_id="{{ $advisor->lecturer_id }}"
                                                data-type="{{ $advisor->type }}"
                                                data-is_approved_for_defense="{{ $advisor->is_approved_for_defense ? '1' : '0' }}"
                                                data-approved_at="{{ $advisor->approved_at ? $advisor->approved_at->format('Y-m-d') : '' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('thesis-advisors.destroy', $advisor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembimbing skripsi ini?')">
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
                                <td colspan="6" class="text-center">Belum ada data pembimbing skripsi.</td>
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
                <h5 class="modal-title">Tambah Pembimbing Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('thesis-advisors.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Skripsi Mahasiswa</label>
                        <select name="thesis_id" class="form-control" required>
                            <option value="">-- Pilih Judul Skripsi --</option>
                            @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Dosen Pembimbing</label>
                        <select name="lecturer_id" class="form-control" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">{{ $lecturer->user->name ?? '' }} (NIDN: {{ $lecturer->nidn }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tipe Pembimbing</label>
                        <select name="type" class="form-control" required>
                            <option value="primary">Utama (Primary)</option>
                            <option value="secondary">Pendamping (Secondary)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_approved_for_defense" class="form-check-input" id="add_is_approved_for_defense" value="1">
                            <label class="form-check-label" for="add_is_approved_for_defense">Setujui untuk Sidang Skripsi</label>
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
                <h5 class="modal-title">Edit Pembimbing Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Skripsi Mahasiswa</label>
                        <select name="thesis_id" id="edit_thesis_id" class="form-control" required>
                            @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Dosen Pembimbing</label>
                        <select name="lecturer_id" id="edit_lecturer_id" class="form-control" required>
                            @foreach($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">{{ $lecturer->user->name ?? '' }} (NIDN: {{ $lecturer->nidn }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tipe Pembimbing</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            <option value="primary">Utama (Primary)</option>
                            <option value="secondary">Pendamping (Secondary)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_approved_for_defense" class="form-check-input" id="edit_is_approved_for_defense" value="1">
                            <label class="form-check-label" for="edit_is_approved_for_defense">Setujui untuk Sidang Skripsi</label>
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
                const thesisId = this.getAttribute('data-thesis_id');
                const lecturerId = this.getAttribute('data-lecturer_id');
                const type = this.getAttribute('data-type');
                const isApproved = this.getAttribute('data-is_approved_for_defense');
                const approvedAt = this.getAttribute('data-approved_at');
                
                document.getElementById('editForm').action = `/admin/thesis-advisors/${id}`;
                document.getElementById('edit_thesis_id').value = thesisId;
                document.getElementById('edit_lecturer_id').value = lecturerId;
                document.getElementById('edit_type').value = type;
                document.getElementById('edit_approved_at').value = approvedAt || '';
                
                document.getElementById('edit_is_approved_for_defense').checked = isApproved === '1';
            });
        });
    });
</script>
@endsection
