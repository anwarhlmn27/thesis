@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Revisi & Persetujuan Pasca-Sidang</h4>
            <p class="mb-0">Kelola berkas revisi skripsi mahasiswa dan verifikasi persetujuan oleh Dosen Penguji & Kaprodi</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Catatan Revisi
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Revisi Sidang & Status Approval (Penguji & Kaprodi)</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>#</strong></th>
                                <th><strong>SIDANG (MAHASISWA)</strong></th>
                                <th><strong>FILE REVISI</strong></th>
                                <th><strong>DESKRIPSI REVISI</strong></th>
                                <th><strong>VERIFIKASI REVISI</strong></th>
                                <th><strong>STATUS YUDISIUM</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revisions as $index => $revision)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($revision->thesisDefense->thesis->title ?? '-', 45) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $revision->thesisDefense->thesis->student->user->name ?? '-' }}</small><br>
                                    <small class="text-info">Penguji: {{ $revision->lecturer->user->name ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($revision->revision_file_path)
                                    @php
                                        $fileUrl = asset(str_starts_with($revision->revision_file_path, 'revisions/') ? 'storage/' . $revision->revision_file_path : $revision->revision_file_path);
                                    @endphp
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-info btn-xs text-white" onclick="previewPdf('{{ $fileUrl }}', 'File Revisi - {{ addslashes($revision->thesisDefense->thesis->student->user->name ?? '') }}')">
                                            <i class="fa fa-eye me-1"></i>Lihat PDF
                                        </button>
                                        <a href="{{ $fileUrl }}" download class="btn btn-outline-secondary btn-xs">
                                            <i class="fa fa-download me-1"></i>Unduh
                                        </a>
                                    </div>
                                    @else
                                    <span class="text-muted fs-12">Belum diunggah</span>
                                    @endif
                                </td>
                                <td><span class="text-wrap d-block" style="max-width:300px;">{{ $revision->description }}</span></td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <!-- EXAMINER -->
                                        <span class="badge {{ $revision->is_approved_by_examiner ? 'badge-success' : 'badge-warning' }}">
                                            <i class="fa {{ $revision->is_approved_by_examiner ? 'fa-check' : 'fa-clock-o' }} me-1"></i>
                                            Penguji: {{ $revision->is_approved_by_examiner ? 'Approved' : 'Pending' }}
                                        </span>
                                        <!-- KAPRODI -->
                                        <span class="badge {{ $revision->is_approved_by_kaprodi ? 'badge-success' : 'badge-warning' }}">
                                            <i class="fa {{ $revision->is_approved_by_kaprodi ? 'fa-check' : 'fa-clock-o' }} me-1"></i>
                                            Kaprodi: {{ $revision->is_approved_by_kaprodi ? 'Approved' : 'Pending' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($revision->is_approved)
                                    <span class="badge badge-success fs-13 py-2 px-3">
                                        <i class="fa fa-check-circle me-1"></i>SIAP YUDISIUM
                                    </span>
                                    @else
                                    <span class="badge badge-warning fs-12 py-1 px-2">
                                        Pending Approval
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-secondary shadow btn-xs me-1 approve-btn" 
                                                data-id="{{ $revision->id }}" 
                                                data-student="{{ $revision->thesisDefense->thesis->student->user->name ?? '' }}"
                                                data-examiner="{{ $revision->is_approved_by_examiner ? '1' : '0' }}"
                                                data-kaprodi="{{ $revision->is_approved_by_kaprodi ? '1' : '0' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal">
                                            <i class="fa fa-check-square-o me-1"></i>Approve
                                        </button>
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $revision->id }}" 
                                                data-thesis_defense_id="{{ $revision->thesis_defense_id }}" 
                                                data-lecturer_id="{{ $revision->lecturer_id }}"
                                                data-description="{{ $revision->description }}"
                                                data-revision_file_path="{{ $revision->revision_file_path }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('defense-revisions.destroy', $revision->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus revisi sidang ini?">
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
                                <td colspan="7" class="text-center">Belum ada data revisi sidang.</td>
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
                <h5 class="modal-title">Tambah Catatan / File Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('defense-revisions.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">Deskripsi / Catatan Revisi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Masukkan detail catatan revisi dari penguji" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Upload File Revisi (Ke Storage / PDF/DOCX)</label>
                        <input type="file" name="revision_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Atau Input Manual Path File (Opsional)</label>
                        <input type="text" name="revision_file_path" class="form-control" placeholder="revisions/revisi_skripsi_final.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Catatan</button>
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
                        <label class="form-label">Path File Revisi Skripsi PDF</label>
                        <input type="text" name="revision_file_path" id="edit_revision_file_path" class="form-control">
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approval Revisi Skripsi Pasca-Sidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <strong id="approve_student">Mahasiswa</strong>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Pilih Peran Verifikator</label>
                        <select name="validator" id="approve_validator" class="form-control" required>
                            <option value="examiner">Dosen Penguji Sidang</option>
                            <option value="kaprodi">Kaprodi (Ketua Program Studi)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Status Persetujuan Revisi</label>
                        <select name="status" id="approve_status" class="form-control" required>
                            <option value="1">✓ Approve (Revisi Diterima)</option>
                            <option value="0">✗ Pending (Revisi Belum Sesuai)</option>
                        </select>
                    </div>
                    <small class="text-muted d-block">Catatan: Apabila disetujui Penguji AND Kaprodi, mahasiswa otomatis terdaftar ke Draft SK Yudisium.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Persetujuan</button>
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
                const filePath = this.getAttribute('data-revision_file_path');
                
                document.getElementById('editForm').action = `/admin/defense-revisions/${id}`;
                document.getElementById('edit_thesis_defense_id').value = defenseId;
                document.getElementById('edit_lecturer_id').value = lecturerId;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_revision_file_path').value = filePath || '';
            });
        });

        const approveBtns = document.querySelectorAll('.approve-btn');
        approveBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const student = this.getAttribute('data-student');

                document.getElementById('approveForm').action = `/admin/defense-revisions/${id}/approve`;
                document.getElementById('approve_student').innerText = student;
            });
        });
    });
</script>
@endsection
