@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Penguji Sidang Skripsi</h4>
            <p class="mb-0">Kelola ketua penguji (chairman), sekretaris (secretary), dan anggota penguji (member) beserta skor nilai sidang</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Penguji Sidang
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
                <h4 class="card-title">Daftar Penguji Sidang Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SIDANG SKRIPSI (MHS)</strong></th>
                                <th><strong>DOSEN PENGUJI</strong></th>
                                <th><strong>JABATAN PENGUJI</strong></th>
                                <th><strong>SKOR DARI PENGUJI</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examiners as $index => $examiner)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($examiner->thesisDefense->thesis->title ?? '-', 45) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $examiner->thesisDefense->thesis->student->user->name ?? '-' }} | Tgl Sidang: {{ $examiner->thesisDefense->defense_date ? $examiner->thesisDefense->defense_date->format('d M Y') : '-' }}</small>
                                </td>
                                <td>{{ $examiner->lecturer->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $examiner->position === 'chairman' ? 'badge-primary' : ($examiner->position === 'secretary' ? 'badge-info' : 'badge-secondary') }}">
                                        {{ $examiner->position === 'chairman' ? 'Ketua Penguji' : ($examiner->position === 'secretary' ? 'Sekretaris' : 'Anggota Penguji') }}
                                    </span>
                                </td>
                                <td><strong>{{ $examiner->score ?? '-' }}</strong></td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $examiner->id }}" 
                                                data-thesis_defense_id="{{ $examiner->thesis_defense_id }}" 
                                                data-lecturer_id="{{ $examiner->lecturer_id }}"
                                                data-position="{{ $examiner->position }}"
                                                data-score="{{ $examiner->score }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('defense-examiners.destroy', $examiner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penguji sidang ini?')">
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
                                <td colspan="6" class="text-center">Belum ada data penguji sidang.</td>
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
                <h5 class="modal-title">Tambah Penguji Sidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('defense-examiners.store') }}" method="POST">
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
                        <label class="form-label">Jabatan Penguji</label>
                        <select name="position" class="form-control" required>
                            <option value="chairman">Ketua Penguji (Chairman)</option>
                            <option value="secretary">Sekretaris (Secretary)</option>
                            <option value="member">Anggota Penguji (Member)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Skor Nilai dari Penguji (0-100)</label>
                        <input type="number" step="0.01" name="score" class="form-control" placeholder="Masukkan skor dari penguji">
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
                <h5 class="modal-title">Edit Penguji Sidang</h5>
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
                        <label class="form-label">Jabatan Penguji</label>
                        <select name="position" id="edit_position" class="form-control" required>
                            <option value="chairman">Ketua Penguji (Chairman)</option>
                            <option value="secretary">Sekretaris (Secretary)</option>
                            <option value="member">Anggota Penguji (Member)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Skor Nilai dari Penguji (0-100)</label>
                        <input type="number" step="0.01" name="score" id="edit_score" class="form-control">
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
                const position = this.getAttribute('data-position');
                const score = this.getAttribute('data-score');
                
                document.getElementById('editForm').action = `/admin/defense-examiners/${id}`;
                document.getElementById('edit_thesis_defense_id').value = defenseId;
                document.getElementById('edit_lecturer_id').value = lecturerId;
                document.getElementById('edit_position').value = position;
                document.getElementById('edit_score').value = score || '';
            });
        });
    });
</script>
@endsection
