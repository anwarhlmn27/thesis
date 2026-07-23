@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Jadwal Seminar Proposal</h4>
            <p class="mb-0">Kelola tanggal, ruangan, dan status kelulusan seminar proposal mahasiswa</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Jadwal
        </button>
    </div>
</div>





<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Jadwal Seminar Proposal</h4>
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
                                <th><strong>STATUS</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seminars as $index => $seminar)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($seminar->thesis->title ?? '-', 50) }}</strong><br>
                                    <small class="text-muted">Mhs: {{ $seminar->thesis->student->user->name ?? '-' }} (NIM: {{ $seminar->thesis->student->nim ?? '-' }})</small>
                                </td>
                                <td>{{ $seminar->seminar_date ? $seminar->seminar_date->format('d M Y - H:i') : '-' }} WIB</td>
                                <td>{{ $seminar->room ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $seminar->status === 'passed' ? 'badge-success' : ($seminar->status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ $seminar->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $seminar->id }}" 
                                                data-thesis_id="{{ $seminar->thesis_id }}" 
                                                data-seminar_date="{{ $seminar->seminar_date ? $seminar->seminar_date->format('Y-m-d\TH:i') : '' }}"
                                                data-room="{{ $seminar->room }}"
                                                data-status="{{ $seminar->status }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('proposal-seminars.destroy', $seminar->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus jadwal seminar ini?">
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
                                <td colspan="6" class="text-center">Belum ada data jadwal seminar.</td>
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
                <h5 class="modal-title">Tambah Jadwal Seminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('proposal-seminars.store') }}" method="POST">
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
                        <label class="form-label">Tanggal & Waktu Seminar</label>
                        <input type="datetime-local" name="seminar_date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" name="room" class="form-control" placeholder="Contoh: Lab 2, Ruang Sidang Utama">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="scheduled">Scheduled</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
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
                <h5 class="modal-title">Edit Jadwal Seminar</h5>
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
                        <label class="form-label">Tanggal & Waktu Seminar</label>
                        <input type="datetime-local" name="seminar_date" id="edit_seminar_date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Ruangan</label>
                        <input type="text" name="room" id="edit_room" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="scheduled">Scheduled</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
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
                const thesisId = this.getAttribute('data-thesis_id');
                const date = this.getAttribute('data-seminar_date');
                const room = this.getAttribute('data-room');
                const status = this.getAttribute('data-status');
                
                document.getElementById('editForm').action = `/admin/proposal-seminars/${id}`;
                document.getElementById('edit_thesis_id').value = thesisId;
                document.getElementById('edit_seminar_date').value = date;
                document.getElementById('edit_room').value = room || '';
                document.getElementById('edit_status').value = status;
            });
        });
    });
</script>
@endsection
