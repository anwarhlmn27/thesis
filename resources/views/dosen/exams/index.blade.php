@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-calendar mr-2 text-white"></i>Jadwal Ujian (Seminar & Sidang)</h4>
                <p class="mb-0 text-white-50">Jadwal ujian di mana Anda ditugaskan sebagai penguji</p>
            </div>
        </div>
    </div>

    <!-- Jadwal Seminar Proposal -->
    <h5 class="mb-3">Jadwal Seminar Proposal</h5>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu & Tempat</th>
                            <th>Mahasiswa</th>
                            <th>Judul Proposal</th>
                            <th>Peran Anda</th>
                            <th>Status Seminar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seminars as $seminar)
                            @php
                                $examinerRole = $seminar->proposalExaminers->where('lecturer_id', auth()->user()->lecturer->id)->first();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $seminar->seminar_date ? \Carbon\Carbon::parse($seminar->seminar_date)->format('d M Y, H:i') : 'Belum ditentukan' }}</strong><br>
                                    <small class="text-muted"><i class="fa fa-map-marker"></i> {{ $seminar->room ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $seminar->thesis->student->user->name }}</strong><br>
                                    <small class="text-muted">NIM: {{ $seminar->thesis->student->nim }}</small>
                                </td>
                                <td>{{ $seminar->thesis->title }}</td>
                                <td>
                                    @if($examinerRole && $examinerRole->position == 'chairman')
                                        <span class="badge badge-primary">Ketua Penguji</span>
                                    @else
                                        <span class="badge badge-secondary">Anggota Penguji</span>
                                    @endif
                                </td>
                                <td>
                                    @if($seminar->status == 'scheduled')
                                        <span class="badge badge-warning">Terjadwal</span>
                                    @elseif($seminar->status == 'completed' || $seminar->status == 'passed')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($seminar->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm {{ ($examinerRole && $examinerRole->status != 'pending') ? 'btn-success' : 'btn-primary' }} eval-btn"
                                            data-id="{{ $seminar->id }}"
                                            data-student="{{ $seminar->thesis->student->user->name }}"
                                            data-title="{{ $seminar->thesis->title }}"
                                            data-status="{{ $examinerRole->status ?? 'pending' }}"
                                            data-notes="{{ $examinerRole->notes ?? '' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#evalModal">
                                        <i class="fa {{ ($examinerRole && $examinerRole->status != 'pending') ? 'fa-check-circle' : 'fa-edit' }}"></i> 
                                        {{ ($examinerRole && $examinerRole->status != 'pending') ? 'Sudah Dinilai (Edit)' : 'Beri Penilaian' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada jadwal seminar proposal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Jadwal Sidang Skripsi -->
    <h5 class="mb-3 mt-5">Jadwal Sidang Skripsi</h5>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu & Tempat</th>
                            <th>Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th>Peran Anda</th>
                            <th>Status Sidang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($defenses as $defense)
                            @php
                                $examinerRole = $defense->defenseExaminers->where('lecturer_id', auth()->user()->lecturer->id)->first();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $defense->defense_date ? \Carbon\Carbon::parse($defense->defense_date)->format('d M Y, H:i') : 'Belum ditentukan' }}</strong><br>
                                    <small class="text-muted"><i class="fa fa-map-marker"></i> {{ $defense->room ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $defense->thesis->student->user->name }}</strong><br>
                                    <small class="text-muted">NIM: {{ $defense->thesis->student->nim }}</small>
                                </td>
                                <td>{{ $defense->thesis->title }}</td>
                                <td>
                                    @if($examinerRole && $examinerRole->position == 'chairman')
                                        <span class="badge badge-primary">Ketua Penguji</span>
                                    @elseif($examinerRole && $examinerRole->position == 'secretary')
                                        <span class="badge badge-info">Sekretaris</span>
                                    @else
                                        <span class="badge badge-secondary">Anggota Penguji</span>
                                    @endif
                                </td>
                                <td>
                                    @if($defense->status == 'scheduled')
                                        <span class="badge badge-warning">Terjadwal</span>
                                    @elseif($defense->status == 'passed' || $defense->status == 'failed')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($defense->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm {{ ($examinerRole && $examinerRole->score !== null) ? 'btn-success' : 'btn-primary' }} eval-defense-btn"
                                            data-id="{{ $defense->id }}"
                                            data-student="{{ $defense->thesis->student->user->name }}"
                                            data-title="{{ $defense->thesis->title }}"
                                            data-score="{{ $examinerRole->score ?? '' }}"
                                            data-notes="{{ $examinerRole->notes ?? '' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#evalDefenseModal">
                                        <i class="fa {{ ($examinerRole && $examinerRole->score !== null) ? 'fa-check-circle' : 'fa-edit' }}"></i> 
                                        {{ ($examinerRole && $examinerRole->score !== null) ? 'Sudah Dinilai (Edit)' : 'Beri Penilaian' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada jadwal sidang skripsi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Evaluasi Seminar Proposal -->
<div class="modal fade" id="evalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penilaian Seminar Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="evalForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <input type="text" id="eval_student" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Proposal</label>
                        <textarea id="eval_title" class="form-control" rows="2" readonly></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Keputusan Kelayakan <span class="text-danger">*</span></label>
                        <select name="status" id="eval_status" class="form-control default-select form-control-lg" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="passed">Lulus (Layak dilanjutkan ke Skripsi)</option>
                            <option value="revision">Revisi (Layak dengan perbaikan)</option>
                            <option value="failed">Tidak Lulus (Harus mengulang/ganti judul)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Catatan Perbaikan (Komentar)</label>
                        <textarea name="notes" id="eval_notes" class="form-control" rows="5" placeholder="Tuliskan catatan revisi, saran, atau masukan untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Evaluasi Sidang Skripsi -->
<div class="modal fade" id="evalDefenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penilaian Sidang Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="evalDefenseForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <input type="text" id="eval_defense_student" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Skripsi</label>
                        <textarea id="eval_defense_title" class="form-control" rows="2" readonly></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="score" id="eval_defense_score" class="form-control form-control-lg" min="0" max="100" step="0.01" required placeholder="Masukkan nilai angka (misal: 85.50)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-primary font-weight-bold">Catatan Perbaikan / Revisi</label>
                        <textarea name="notes" id="eval_defense_notes" class="form-control" rows="5" placeholder="Tuliskan catatan revisi, perbaikan, atau masukan untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Evaluasi Seminar Proposal
        const evalBtns = document.querySelectorAll('.eval-btn');
        evalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const student = this.getAttribute('data-student');
                const title = this.getAttribute('data-title');
                const status = this.getAttribute('data-status');
                const notes = this.getAttribute('data-notes');
                
                document.getElementById('evalForm').action = `/dosen/exams/proposal/${id}/evaluate`;
                document.getElementById('eval_student').value = student;
                document.getElementById('eval_title').value = title;
                document.getElementById('eval_notes').value = notes;
                
                if(status && status != 'pending') {
                    $('#eval_status').val(status).trigger('change');
                } else {
                    $('#eval_status').val('').trigger('change');
                }
                
                // Refresh bootstrap-select for UI update
                if ($('#eval_status').hasClass('default-select')) {
                    $('#eval_status').selectpicker('refresh');
                }
            });
        });

        // Evaluasi Sidang Skripsi
        const evalDefenseBtns = document.querySelectorAll('.eval-defense-btn');
        evalDefenseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const student = this.getAttribute('data-student');
                const title = this.getAttribute('data-title');
                const score = this.getAttribute('data-score');
                const notes = this.getAttribute('data-notes');
                
                document.getElementById('evalDefenseForm').action = `/dosen/exams/defense/${id}/evaluate`;
                document.getElementById('eval_defense_student').value = student;
                document.getElementById('eval_defense_title').value = title;
                document.getElementById('eval_defense_score').value = score;
                document.getElementById('eval_defense_notes').value = notes;
            });
        });
    });
</script>
@endsection
