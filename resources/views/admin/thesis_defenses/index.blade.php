@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Pendaftaran & Jadwal Sidang Skripsi</h4>
            <p class="mb-0">Kelola pendaftaran sidang skripsi, tanggal sidang, tim penguji, dan verifikasi 5 prasyarat kelayakan sidang</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Pendaftaran & Jadwal Sidang Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>#</strong></th>
                                <th><strong>SKRIPSI (MAHASISWA)</strong></th>
                                <th><strong>TANGGAL & RUANG</strong></th>
                                <th><strong>FILE FINAL SKRIPSI</strong></th>
                                <th><strong>NILAI & STATUS</strong></th>
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
                                <td>
                                    {{ $defense->defense_date ? $defense->defense_date->format('d M Y, H:i') : '-' }} WIB<br>
                                    <small class="text-muted"><i class="fa fa-map-marker me-1"></i>Ruang: {{ $defense->room ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($defense->thesis && $defense->thesis->final_file_path)
                                    @php
                                        $fileUrl = asset(str_starts_with($defense->thesis->final_file_path, 'theses/final/') ? 'storage/' . $defense->thesis->final_file_path : (str_starts_with($defense->thesis->final_file_path, 'final_theses/') ? 'storage/' . $defense->thesis->final_file_path : $defense->thesis->final_file_path));
                                    @endphp
                                    <div class="d-flex flex-wrap gap-1 mb-1">
                                        <button type="button" class="btn btn-info btn-xs text-white" onclick="previewPdf('{{ $fileUrl }}', 'Skripsi Akhir - {{ addslashes($defense->thesis->student->user->name ?? '') }}')">
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
                                <td>
                                    <strong>Skor:</strong> {{ $defense->score ?? '-' }} | 
                                    <strong>Grade:</strong> <span class="badge badge-info">{{ $defense->grade ?? '-' }}</span><br>
                                    <span class="badge {{ $defense->status === 'passed' ? 'badge-success' : ($defense->status === 'failed' ? 'badge-danger' : 'badge-warning') }} text-capitalize mt-1">
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
                                                data-final_file_path="{{ $defense->thesis->final_file_path ?? '' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('thesis-defenses.destroy', $defense->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus jadwal sidang ini?">
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
                                <td colspan="6" class="text-center">Belum ada pendaftaran sidang skripsi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Status / Jadwal Sidang</h5>
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
                            <option value="{{ $thesis->id }}">{{ $thesis->student->nim ?? '' }} - {{ $thesis->student->user->name ?? '' }} | {{ Str::limit($thesis->title, 40) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal & Waktu Sidang</label>
                            <input type="datetime-local" name="defense_date" id="edit_defense_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ruangan Sidang</label>
                            <input type="text" name="room" id="edit_room" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status Sidang</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="registered">Registered (Pendaftaran Baru)</option>
                            <option value="scheduled">Scheduled (Jadwal Terbit)</option>
                            <option value="passed">Passed (Lulus Sidang)</option>
                            <option value="failed">Failed (Tidak Lulus)</option>
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
                const date = this.getAttribute('data-defense_date');
                const room = this.getAttribute('data-room');
                const status = this.getAttribute('data-status');
                const score = this.getAttribute('data-score');
                const grade = this.getAttribute('data-grade');
                const finalFile = this.getAttribute('data-final_file_path');
                
                document.getElementById('editForm').action = `/admin/thesis-defenses/${id}`;
                document.getElementById('edit_thesis_id').value = thesisId;
                document.getElementById('edit_defense_date').value = date;
                document.getElementById('edit_room').value = room || '';
                document.getElementById('edit_status').value = status;
                if(document.getElementById('edit_score')) document.getElementById('edit_score').value = score || '';
                if(document.getElementById('edit_grade')) document.getElementById('edit_grade').value = grade || '';
                if(document.getElementById('edit_final_file_path')) document.getElementById('edit_final_file_path').value = finalFile || '';
            });
        });
    });
</script>
@endsection
