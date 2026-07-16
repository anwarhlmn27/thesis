@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Data Yudisium</h4>
            <p class="mb-0">Kelola status kelulusan, nomor SK yudisium, dan tanggal wisuda mahasiswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Yudisium
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
                <h4 class="card-title">Daftar Yudisium Mahasiswa</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>MAHASISWA</strong></th>
                                <th><strong>SKRIPSI</strong></th>
                                <th><strong>NOMOR SK</strong></th>
                                <th><strong>TANGGAL KELULUSAN</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($yudisiums as $index => $yudisium)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ $yudisium->student->user->name ?? '-' }}</strong><br>
                                    <small class="text-muted">NIM: {{ $yudisium->student->nim ?? '-' }}</small>
                                </td>
                                <td>{{ Str::limit($yudisium->thesis->title ?? '-', 50) }}</td>
                                <td>
                                    {{ $yudisium->sk_number ?? '-' }}
                                    @if($yudisium->sk_file_path)
                                    <br><small class="text-muted"><i class="fa fa-file-pdf-o"></i> Path: {{ $yudisium->sk_file_path }}</small>
                                    @endif
                                </td>
                                <td>{{ $yudisium->graduation_date ? $yudisium->graduation_date->format('d M Y') : '-' }}</td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $yudisium->id }}" 
                                                data-student_id="{{ $yudisium->student_id }}" 
                                                data-thesis_id="{{ $yudisium->thesis_id }}"
                                                data-sk_number="{{ $yudisium->sk_number }}"
                                                data-sk_file_path="{{ $yudisium->sk_file_path }}"
                                                data-graduation_date="{{ $yudisium->graduation_date ? $yudisium->graduation_date->format('Y-m-d') : '' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('yudisiums.destroy', $yudisium->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yudisium ini?')">
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
                                <td colspan="6" class="text-center">Belum ada data yudisium.</td>
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
                <h5 class="modal-title">Tambah Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('yudisiums.store') }}" method="POST">
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
                        <label class="form-label">Skripsi</label>
                        <select name="thesis_id" class="form-control" required>
                            <option value="">-- Pilih Judul Skripsi --</option>
                            @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Nomor SK Yudisium</label>
                        <input type="text" name="sk_number" class="form-control" placeholder="Contoh: 123/SK/YUD/2026">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Path File SK</label>
                        <input type="text" name="sk_file_path" class="form-control" placeholder="uploads/sk_yudisium.pdf">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal Kelulusan / Yudisium</label>
                        <input type="date" name="graduation_date" class="form-control">
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
                <h5 class="modal-title">Edit Data Yudisium</h5>
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
                        <label class="form-label">Skripsi</label>
                        <select name="thesis_id" id="edit_thesis_id" class="form-control" required>
                            @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Nomor SK Yudisium</label>
                        <input type="text" name="sk_number" id="edit_sk_number" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Path File SK</label>
                        <input type="text" name="sk_file_path" id="edit_sk_file_path" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal Kelulusan / Yudisium</label>
                        <input type="date" name="graduation_date" id="edit_graduation_date" class="form-control">
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
                const thesisId = this.getAttribute('data-thesis_id');
                const skNumber = this.getAttribute('data-sk_number');
                const skFilePath = this.getAttribute('data-sk_file_path');
                const date = this.getAttribute('data-graduation_date');
                
                document.getElementById('editForm').action = `/admin/yudisiums/${id}`;
                document.getElementById('edit_student_id').value = studentId;
                document.getElementById('edit_thesis_id').value = thesisId;
                document.getElementById('edit_sk_number').value = skNumber || '';
                document.getElementById('edit_sk_file_path').value = skFilePath || '';
                document.getElementById('edit_graduation_date').value = date;
            });
        });
    });
</script>
@endsection
