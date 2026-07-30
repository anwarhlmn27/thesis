@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Draft & Penetapan SK Yudisium</h4>
            <p class="mb-0">Kelola draft SK Yudisium mahasiswa yang eligible, penerbitan nomor SK, dan cetak SK resmi bertanda tangan Dekan</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Draft SK Yudisium
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar SK Yudisium & Kelulusan Mahasiswa</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>#</strong></th>
                                <th><strong>TAHUN AKADEMIK & MAHASISWA</strong></th>
                                <th><strong>NOMOR SK YUDISIUM</strong></th>
                                <th><strong>TANGGAL KELULUSAN</strong></th>
                                <th><strong>STATUS DRAFT / SK</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($yudisiums as $index => $yudisium)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>TA: {{ $yudisium->academic_year ?? '-' }}</strong><br>
                                    <span class="badge badge-primary">{{ $yudisium->students->count() }} Mahasiswa Dilampirkan</span>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $yudisium->sk_number ?? '-' }}</strong>
                                    <br><small class="text-muted">Dekan: {{ $yudisium->dekan_name ?? 'Dr. H. Ahmad Dahlan, M.Pd.' }}</small>
                                </td>
                                <td>{{ $yudisium->graduation_date ? $yudisium->graduation_date->format('d M Y') : '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match($yudisium->status) {
                                            'printed' => 'badge-success',
                                            'approved' => 'badge-info',
                                            default => 'badge-warning'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-capitalize">
                                        {{ $yudisium->status ?? 'draft' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($yudisium->sk_file_path)
                                        @php
                                            $skFileUrl = asset(str_starts_with($yudisium->sk_file_path, 'yudisiums/') ? 'storage/' . $yudisium->sk_file_path : $yudisium->sk_file_path);
                                        @endphp
                                        <button type="button" class="btn btn-info shadow btn-xs text-white me-1" onclick="previewPdf('{{ $skFileUrl }}', 'SK Yudisium - {{ addslashes($yudisium->student->user->name ?? '') }}')">
                                            <i class="fa fa-eye me-1"></i>Lihat SK
                                        </button>
                                        <a href="{{ $skFileUrl }}" download class="btn btn-outline-secondary shadow btn-xs me-1">
                                            <i class="fa fa-download me-1"></i>Unduh SK
                                        </a>
                                        @endif
                                        <a href="{{ route('yudisiums.print', $yudisium->id) }}" target="_blank" class="btn btn-success shadow btn-xs me-1">
                                            <i class="fa fa-print me-1"></i>Cetak Draft SK
                                        </a>
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $yudisium->id }}" 
                                                data-sk_number="{{ $yudisium->sk_number }}"
                                                data-academic_year="{{ $yudisium->academic_year }}"
                                                data-sk_file_path="{{ $yudisium->sk_file_path }}"
                                                data-dekan_name="{{ $yudisium->dekan_name }}"
                                                data-dekan_nip="{{ $yudisium->dekan_nip }}"
                                                data-status="{{ $yudisium->status }}"
                                                data-graduation_date="{{ $yudisium->graduation_date ? $yudisium->graduation_date->format('Y-m-d') : '' }}"
                                                data-students="{{ $yudisium->students->map(function($s) { return ['id' => $s->id, 'ipk' => $s->pivot->ipk, 'predicate' => $s->pivot->predicate]; })->toJson() }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('yudisiums.destroy', $yudisium->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus data yudisium ini?">
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Draft SK Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('yudisiums.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor SK Yudisium</label>
                            <input type="text" name="sk_number" class="form-control" placeholder="Contoh: SK-YUD/2026/0001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <input type="text" name="academic_year" class="form-control" placeholder="Contoh: 2028/2029">
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Pilih Mahasiswa & Input Nilai (Lampiran)</label>
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto; border: 1px solid #eee;">
                            <table class="table table-bordered table-sm mb-0">
                                <thead style="position: sticky; top: 0; background: #f8f9fa; z-index: 1;">
                                    <tr>
                                        <th style="width: 30px;" class="text-center"><i class="fa fa-check"></i></th>
                                        <th>NIM - Nama</th>
                                        <th style="width: 100px;">IPK</th>
                                        <th>Predikat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td class="text-center align-middle">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}">
                                        </td>
                                        <td class="align-middle" style="font-size: 13px;">
                                            <strong>{{ $student->nim }}</strong><br>
                                            {{ $student->user->name ?? '' }} ({{ $student->prodi }})
                                        </td>
                                        <td>
                                            <input type="number" name="ipk[{{ $student->id }}]" step="0.01" min="0" max="4" class="form-control form-control-sm" placeholder="3.00">
                                        </td>
                                        <td>
                                            <input type="text" name="predicate[{{ $student->id }}]" class="form-control form-control-sm" placeholder="Sangat Memuaskan">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kelulusan / Yudisium</label>
                            <input type="date" name="graduation_date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Upload Berkas SK Yudisium (PDF Ke Folder Storage)</label>
                        <input type="file" name="sk_file" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Dekan Penandatangan</label>
                            <input type="text" name="dekan_name" class="form-control" value="Dr. H. Ahmad Dahlan, M.Pd.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP Dekan</label>
                            <input type="text" name="dekan_nip" class="form-control" value="197508152002121001">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status SK Yudisium</label>
                        <select name="status" class="form-control" required>
                            <option value="draft">Draft SK</option>
                            <option value="approved">Approved / Disetujui Dekan</option>
                            <option value="printed">Printed / Dicetak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Draft SK</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data SK Yudisium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor SK Yudisium</label>
                            <input type="text" name="sk_number" id="edit_sk_number" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <input type="text" name="academic_year" id="edit_academic_year" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Pilih Mahasiswa & Input Nilai (Lampiran)</label>
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto; border: 1px solid #eee;">
                            <table class="table table-bordered table-sm mb-0">
                                <thead style="position: sticky; top: 0; background: #f8f9fa; z-index: 1;">
                                    <tr>
                                        <th style="width: 30px;" class="text-center"><i class="fa fa-check"></i></th>
                                        <th>NIM - Nama</th>
                                        <th style="width: 100px;">IPK</th>
                                        <th>Predikat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td class="text-center align-middle">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="edit-student-checkbox" data-student-id="{{ $student->id }}">
                                        </td>
                                        <td class="align-middle" style="font-size: 13px;">
                                            <strong>{{ $student->nim }}</strong><br>
                                            {{ $student->user->name ?? '' }} ({{ $student->prodi }})
                                        </td>
                                        <td>
                                            <input type="number" name="ipk[{{ $student->id }}]" id="edit_ipk_{{ $student->id }}" step="0.01" min="0" max="4" class="form-control form-control-sm" placeholder="3.00">
                                        </td>
                                        <td>
                                            <input type="text" name="predicate[{{ $student->id }}]" id="edit_predicate_{{ $student->id }}" class="form-control form-control-sm" placeholder="Sangat Memuaskan">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kelulusan / Yudisium</label>
                            <input type="date" name="graduation_date" id="edit_graduation_date" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Dekan Penandatangan</label>
                            <input type="text" name="dekan_name" id="edit_dekan_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP Dekan</label>
                            <input type="text" name="dekan_nip" id="edit_dekan_nip" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status SK Yudisium</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="draft">Draft SK</option>
                            <option value="approved">Approved / Disetujui Dekan</option>
                            <option value="printed">Printed / Dicetak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update SK</button>
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
                const skNumber = this.getAttribute('data-sk_number');
                const academicYear = this.getAttribute('data-academic_year');
                const date = this.getAttribute('data-graduation_date');
                const dekanName = this.getAttribute('data-dekan_name');
                const dekanNip = this.getAttribute('data-dekan_nip');
                const status = this.getAttribute('data-status');
                const studentsJson = this.getAttribute('data-students');
                
                document.getElementById('editForm').action = `/admin/yudisiums/${id}`;
                document.getElementById('edit_sk_number').value = skNumber || '';
                document.getElementById('edit_academic_year').value = academicYear || '';
                document.getElementById('edit_graduation_date').value = date;
                document.getElementById('edit_dekan_name').value = dekanName || '';
                document.getElementById('edit_dekan_nip').value = dekanNip || '';
                document.getElementById('edit_status').value = status || 'draft';

                // Reset all checkboxes and inputs first
                document.querySelectorAll('.edit-student-checkbox').forEach(cb => cb.checked = false);
                document.querySelectorAll('[id^="edit_ipk_"]').forEach(inp => inp.value = '');
                document.querySelectorAll('[id^="edit_predicate_"]').forEach(inp => inp.value = '');

                // Fill in the students for this yudisium
                if (studentsJson) {
                    try {
                        const students = JSON.parse(studentsJson);
                        students.forEach(student => {
                            const cb = document.querySelector(`.edit-student-checkbox[data-student-id="${student.id}"]`);
                            if (cb) cb.checked = true;
                            
                            const ipkInput = document.getElementById(`edit_ipk_${student.id}`);
                            if (ipkInput) ipkInput.value = student.ipk;
                            
                            const predInput = document.getElementById(`edit_predicate_${student.id}`);
                            if (predInput) predInput.value = student.predicate;
                        });
                    } catch (e) {
                        console.error('Failed to parse students JSON', e);
                    }
                }
            });
        });
    });
</script>
@endsection
