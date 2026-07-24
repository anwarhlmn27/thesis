@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Plotting Pembimbing</h4>
            <p class="mb-0">Atur dosen pembimbing untuk mahasiswa yang proposalnya disetujui</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Pembimbing</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Mahasiswa & Pembimbing</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>#</strong></th>
                                <th><strong>MAHASISWA</strong></th>
                                <th><strong>JUDUL SKRIPSI</strong></th>
                                <th><strong>PEMBIMBING SAAT INI</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($theses as $index => $thesis)
                            @php
                                $pembimbing1 = $thesis->thesisAdvisors->where('type', 'primary')->first();
                                $pembimbing2 = $thesis->thesisAdvisors->where('type', 'secondary')->first();
                            @endphp
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="w-space-no">{{ $thesis->student->user->name ?? '-' }}</span>
                                    </div>
                                    <small class="text-muted">{{ $thesis->student->nim ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $thesis->title }}</strong>
                                </td>
                                <td>
                                    @if($pembimbing1)
                                        <span class="badge badge-primary light badge-sm mb-1">
                                            1: {{ $pembimbing1->lecturer->user->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning light badge-sm mb-1">Belum diplot</span>
                                    @endif
                                    
                                    @if($pembimbing2)
                                        <br>
                                        <span class="badge badge-info light badge-sm">
                                            2: {{ $pembimbing2->lecturer->user->name }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary shadow-sm plot-btn" 
                                            data-id="{{ $thesis->id }}" 
                                            data-name="{{ $thesis->student->user->name ?? '' }}"
                                            data-title="{{ $thesis->title }}"
                                            data-p1="{{ $pembimbing1 ? $pembimbing1->lecturer_id : '' }}"
                                            data-p2="{{ $pembimbing2 ? $pembimbing2->lecturer_id : '' }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#plotModal">
                                        <i class="fa fa-users me-1"></i> Atur Pembimbing
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada mahasiswa yang proposalnya disetujui.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plotting Modal -->
<div class="modal fade" id="plotModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atur Dosen Pembimbing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kaprodi.advisors.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="thesis_id" id="plot_thesis_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <input type="text" id="plot_student_name" class="form-control" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Judul Skripsi</label>
                        <textarea id="plot_thesis_title" class="form-control" rows="2" readonly></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pembimbing 1 <span class="text-danger">*</span></label>
                        <select name="lecturer_id_1" id="plot_lecturer_1" class="form-control default-select form-control-lg" required>
                            <option value="">-- Pilih Pembimbing 1 --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }} ({{ $lecturer->nidn }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pembimbing 2 (Opsional)</label>
                        <select name="lecturer_id_2" id="plot_lecturer_2" class="form-control default-select form-control-lg">
                            <option value="">-- Tidak ada Pembimbing 2 --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }} ({{ $lecturer->nidn }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Plotting</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const plotBtns = document.querySelectorAll('.plot-btn');
        plotBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('plot_thesis_id').value = this.getAttribute('data-id');
                document.getElementById('plot_student_name').value = this.getAttribute('data-name');
                document.getElementById('plot_thesis_title').value = this.getAttribute('data-title');
                
                const p1 = this.getAttribute('data-p1');
                const p2 = this.getAttribute('data-p2');
                
                // Set the select values
                if(p1) {
                    $('#plot_lecturer_1').val(p1).trigger('change');
                } else {
                    $('#plot_lecturer_1').val('').trigger('change');
                }
                
                if(p2) {
                    $('#plot_lecturer_2').val(p2).trigger('change');
                } else {
                    $('#plot_lecturer_2').val('').trigger('change');
                }
            });
        });
    });
</script>
@endsection
