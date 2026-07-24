@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Proposal Skripsi</h4>
            <p class="mb-0">Kelola pendaftaran proposal skripsi dan verifikasi kelayakan (eligibility) seminar oleh Kaprodi, BAAK, dan Finance</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-upload me-2"></i>Upload / Daftar Proposal
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Proposal Skripsi & Status Verification Eligibility</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>#</strong></th>
                                <th><strong>MAHASISWA & JUDUL SKRIPSI</strong></th>
                                <th><strong>BERKAS PROPOSAL</strong></th>
                                <th><strong>APPROVAL 3 PIHAK</strong></th>
                                <th><strong>STATUS KELAYAKAN</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposals as $index => $prop)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ $prop->thesis->student->user->name ?? '-' }}</strong><br>
                                    <small class="text-muted">NIM: {{ $prop->thesis->student->nim ?? '-' }}</small><br>
                                    <span class="text-dark fw-bold d-block mt-1">{{ $prop->thesis->title ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($prop->proposal_file_path)
                                    @php
                                        $fileUrl = asset(str_starts_with($prop->proposal_file_path, 'proposals/') ? 'storage/' . $prop->proposal_file_path : $prop->proposal_file_path);
                                    @endphp
                                    <div class="d-flex flex-wrap gap-1 mb-1">
                                        <button type="button" class="btn btn-info btn-xs text-white" onclick="previewPdf('{{ $fileUrl }}', 'Proposal Skripsi - {{ addslashes($prop->thesis->student->user->name ?? '') }}')">
                                            <i class="fa fa-eye me-1"></i>Lihat PDF
                                        </button>
                                        <a href="{{ $fileUrl }}" download class="btn btn-outline-secondary btn-xs">
                                            <i class="fa fa-download me-1"></i>Unduh
                                        </a>
                                    </div>
                                    @else
                                    <span class="badge bg-light text-muted">Belum ada berkas</span>
                                    @endif
                                    <small class="text-muted d-block"><i class="fa fa-clock-o me-1"></i>{{ $prop->submission_date ? $prop->submission_date->format('d M Y, H:i') : '-' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <!-- BAAK -->
                                        <div>
                                            <span class="badge {{ $prop->is_baak_approved ? 'badge-success' : 'badge-warning' }}">
                                                <i class="fa {{ $prop->is_baak_approved ? 'fa-check' : 'fa-hourglass-half' }} me-1"></i>
                                                BAAK: {{ $prop->is_baak_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                            @if($prop->baak_notes)
                                            <small class="text-muted d-block ms-1"><i>Note: {{ $prop->baak_notes }}</i></small>
                                            @endif
                                        </div>
                                        <!-- FINANCE -->
                                        <div>
                                            <span class="badge {{ $prop->is_finance_approved ? 'badge-success' : 'badge-warning' }}">
                                                <i class="fa {{ $prop->is_finance_approved ? 'fa-check' : 'fa-hourglass-half' }} me-1"></i>
                                                FINANCE: {{ $prop->is_finance_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                            @if($prop->finance_notes)
                                            <small class="text-muted d-block ms-1"><i>Note: {{ $prop->finance_notes }}</i></small>
                                            @endif
                                        </div>
                                        <!-- KAPRODI -->
                                        <div>
                                            <span class="badge {{ $prop->is_kaprodi_approved ? 'badge-success' : 'badge-warning' }}">
                                                <i class="fa {{ $prop->is_kaprodi_approved ? 'fa-check' : 'fa-hourglass-half' }} me-1"></i>
                                                KAPRODI: {{ $prop->is_kaprodi_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                            @if($prop->kaprodi_notes)
                                            <small class="text-muted d-block ms-1"><i>Note: {{ $prop->kaprodi_notes }}</i></small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($prop->eligibility_status === 'eligible')
                                    <span class="badge badge-success fs-14 py-2 px-3">
                                        <i class="fa fa-check-circle me-1"></i>ELIGIBLE (3/3)
                                    </span>
                                    <br>
                                    <a href="{{ route('proposal-seminars.index') }}" class="btn btn-xs btn-outline-primary mt-2">
                                        <i class="fa fa-calendar me-1"></i>Jadwalkan Seminar
                                    </a>
                                    @else
                                    <span class="badge badge-warning fs-13 py-2 px-3">
                                        <i class="fa fa-clock-o me-1"></i>Belum Eligible
                                    </span>
                                    <br><small class="text-muted">Menunggu approval 3/3</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-secondary shadow btn-xs me-1 approve-btn" 
                                                data-id="{{ $prop->id }}" 
                                                data-student="{{ $prop->thesis->student->user->name ?? '' }}"
                                                data-title="{{ $prop->thesis->title ?? '' }}"
                                                data-baak="{{ $prop->is_baak_approved ? '1' : '0' }}"
                                                data-baak-notes="{{ $prop->baak_notes ?? '' }}"
                                                data-finance="{{ $prop->is_finance_approved ? '1' : '0' }}"
                                                data-finance-notes="{{ $prop->finance_notes ?? '' }}"
                                                data-kaprodi="{{ $prop->is_kaprodi_approved ? '1' : '0' }}"
                                                data-kaprodi-notes="{{ $prop->kaprodi_notes ?? '' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal">
                                            <i class="fa fa-check-square-o me-1"></i>Verifikasi
                                        </button>
                                        <form action="{{ route('thesis-proposals.destroy', $prop->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus proposal ini?">
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
                                <td colspan="6" class="text-center">Belum ada proposal skripsi yang didaftarkan.</td>
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
                <h5 class="modal-title">Upload / Daftar Proposal Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('thesis-proposals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Judul Skripsi / Mahasiswa</label>
                        <select name="thesis_id" class="form-control" required>
                            <option value="">-- Pilih Skripsi Mahasiswa --</option>
                            @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">
                                {{ $thesis->student->nim ?? '' }} - {{ $thesis->student->user->name ?? '' }} | {{ Str::limit($thesis->title, 50) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Upload Berkas Proposal (Ke Folder Storage / PDF/DOCX)</label>
                        <input type="file" name="proposal_file" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Atau Input Manual Path File (Opsional)</label>
                        <input type="text" name="proposal_file_path" class="form-control" placeholder="proposals/proposal_skripsi.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Proposal</button>
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
                <h5 class="modal-title">Verifikasi Kelayakan (Eligibility) Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <strong id="approve_student">Mahasiswa</strong><br>
                        <small id="approve_title">Judul Skripsi</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Pilih Pihak Verifikator</label>
                        <select name="validator" id="approve_validator" class="form-control" required>
                            <option value="baak">BAAK (Administrasi Akademik)</option>
                            <option value="finance">Finance (Status Keuangan/UKT)</option>
                            <option value="kaprodi">Kaprodi (Ketua Program Studi)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Keputusan Status Eligibility</label>
                        <select name="status" id="approve_status" class="form-control" required>
                            <option value="1">✓ Setujui (Approve / Eligible)</option>
                            <option value="0">✗ Belum / Batalkan Persetujuan (Pending)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Catatan Verifikator (Opsional)</label>
                        <textarea name="notes" id="approve_notes" class="form-control" rows="3" placeholder="Masukkan catatan atau keterangan pendukung..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const approveBtns = document.querySelectorAll('.approve-btn');
        approveBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const student = this.getAttribute('data-student');
                const title = this.getAttribute('data-title');

                document.getElementById('approveForm').action = `/admin/thesis-proposals/${id}/approve`;
                document.getElementById('approve_student').innerText = student;
                document.getElementById('approve_title').innerText = title;
            });
        });
    });
</script>
@endsection
