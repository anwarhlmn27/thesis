@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Jadwal Sidang Skripsi</h4>
            <p class="mb-0">Kelola tanggal sidang, ruangan, skor akhir, nilai huruf, dan status kelulusan mahasiswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Jadwal Sidang
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
                <h4 class="card-title">Daftar Jadwal Sidang Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>SKRIPSI (MAHASISWA)</strong></th>
                                <th><strong>TANGGAL & WAKTU</strong></th>
                                <th><strong>RUANGAN</strong></th>
                                <th><strong>NILAI (SKOR/GRADE)</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($defenses as $index => $defense)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($defense->thesis->title ?? '-', 50) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $defense->thesis->student->user->name ?? '-' }} (NIM: {{ $defense->thesis->student->nim ?? '-' }})</small>
                                </td>
                                <td>{{ $defense->defense_date ? $defense->defense_date->format('d M Y - H:i') : '-' }} WIB</td>
                                <td>{{ $defense->room ?? '-' }}</td>
                                <td>
                                    <strong>Skor:</strong> {{ $defense->score ?? '-' }}<br>
                                    <strong>Grade:</strong> <span class="badge badge-info">{{ $defense->grade ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $defense->status === 'passed' ? 'badge-success' : ($defense->status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ $defense->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $defense->id }}" 
                                                data-thesis_id="{{ $defense->thesis_id }}" 
                                                data-defense_date="{{ $defense->defense_date ? $defense->defense_date->format('Y-m-d\TH:i') : '' }}"
                                                data-room="{{ $defense->room }}"
                                                data-status="{{ $defense->status }}"
                                                data-score="{{ $defense->score }}"
                                                data-grade="{{ $defense->grade }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('thesis-defenses.destroy', $defense->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal sidang ini?')">
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
                                <td colspan="7" class="text-center">Belum ada data jadwal sidang.</td>
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
                <h5 class="modal-title">Tambah Jadwal Sidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('thesis-defenses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Skripsi Mahasiswa</label>
                        <select name="thesis_id" class="form-control" required>
                            <option value="">-- Pilih Skripsi --</option>
                            @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">{{ $thesis->student->nim }} - {{ Str::limit($thesis->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tanggal & Waktu Sidang</label>
                        <input type="datetime-local" name="defense_date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" name="room" class="form-control" placeholder="Contoh: Ruang Sidang FEB, Ruang 103">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Sidang</label>
                        <select name="status" class="form-control" required>
                            <option value="registered">Registered</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Skor Akhir (0-100)</label>
                            <input type="number" step="0.01" name="score" class="form-control" placeholder="Contoh: 85.50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grade (Nilai Huruf)</label>
                            <input type="text" name="grade" class="form-control" placeholder="Contoh: A, B+, C">
                        </div>
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
                <h5 class="modal-title">Edit Jadwal Sidang</h5>
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
                        <label class="form-label">Tanggal & Waktu Sidang</label>
                        <input type="datetime-local" name="defense_date" id="edit_defense_date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" name="room" id="edit_room" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Sidang</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="registered">Registered</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Skor Akhir (0-100)</label>
                            <input type="number" step="0.01" name="score" id="edit_score" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grade (Nilai Huruf)</label>
                            <input type="text" name="grade" id="edit_grade" class="form-control">
                        </div>
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
                const date = this.getAttribute('data-defense_date');
                const room = this.getAttribute('data-room');
                const status = this.getAttribute('data-status');
                const score = this.getAttribute('data-score');
                const grade = this.getAttribute('data-grade');
                
                document.getElementById('editForm').action = `/admin/thesis-defenses/${id}`;
                document.getElementById('edit_thesis_id').value = thesisId;
                document.getElementById('edit_defense_date').value = date;
                document.getElementById('edit_room').value = room || '';
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_score').value = score || '';
                document.getElementById('edit_grade').value = grade || '';
            });
        });
    });
</script>
@endsection
