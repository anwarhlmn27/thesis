@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Plotting Penguji</h4>
            <p class="mb-0">Atur dosen penguji untuk jadwal seminar proposal dan sidang skripsi</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Penguji</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Daftar Jadwal Ujian</h4>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#seminar">Seminar Proposal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#sidang">Sidang Skripsi</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content pt-3">
                    <!-- Seminar Proposal Tab -->
                    <div class="tab-pane active" id="seminar" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:50px;"><strong>#</strong></th>
                                        <th><strong>MAHASISWA & JUDUL</strong></th>
                                        <th><strong>WAKTU & RUANGAN</strong></th>
                                        <th><strong>PENGUJI SAAT INI</strong></th>
                                        <th><strong>AKSI</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($seminars as $index => $seminar)
                                    @php
                                        $ketua = $seminar->proposalExaminers->where('position', 'chairman')->first();
                                        $anggota = $seminar->proposalExaminers->where('position', 'member')->values();
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="w-space-no fw-bold">{{ $seminar->thesis->student->user->name ?? '-' }}</span>
                                            </div>
                                            <small class="text-muted">{{ $seminar->thesis->student->nim ?? '-' }}</small><br>
                                            <small><em>{{ $seminar->thesis->title }}</em></small>
                                        </td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($seminar->seminar_date)->format('d M Y, H:i') }}</strong><br>
                                            <span class="badge badge-light mt-1"><i class="fa fa-map-marker me-1"></i>{{ $seminar->room ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($ketua)
                                                <span class="badge badge-primary light badge-sm mb-1" title="Ketua Penguji">
                                                    K: {{ $ketua->lecturer->user->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-warning light badge-sm mb-1">Ketua: Belum diplot</span>
                                            @endif
                                            
                                            @if($anggota->count() > 0)
                                                @foreach($anggota as $i => $ang)
                                                <br>
                                                <span class="badge badge-info light badge-sm mb-1" title="Anggota Penguji {{ $i+1 }}">
                                                    A{{ $i+1 }}: {{ $ang->lecturer->user->name }}
                                                </span>
                                                @endforeach
                                            @else
                                                <br>
                                                <span class="badge badge-warning light badge-sm mb-1">Anggota: Belum diplot</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary shadow-sm plot-btn" 
                                                    data-id="{{ $seminar->id }}" 
                                                    data-name="{{ $seminar->thesis->student->user->name ?? '' }}"
                                                    data-date="{{ \Carbon\Carbon::parse($seminar->seminar_date)->format('d M Y, H:i') }}"
                                                    data-p1="{{ $ketua ? $ketua->lecturer_id : '' }}"
                                                    data-p2="{{ $anggota->count() > 0 ? $anggota[0]->lecturer_id : '' }}"
                                                    data-p3="{{ $anggota->count() > 1 ? $anggota[1]->lecturer_id : '' }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#plotModal">
                                                <i class="fa fa-users me-1"></i> Atur Penguji
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada jadwal seminar yang diterbitkan BAAK.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sidang Skripsi Tab -->
                    <div class="tab-pane fade" id="sidang" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:50px;"><strong>#</strong></th>
                                        <th><strong>MAHASISWA & JUDUL</strong></th>
                                        <th><strong>WAKTU & RUANGAN</strong></th>
                                        <th><strong>PENGUJI SAAT INI</strong></th>
                                        <th><strong>AKSI</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($defenses as $index => $defense)
                                    @php
                                        $ketua = $defense->defenseExaminers->where('position', 'chairman')->first();
                                        $anggota = $defense->defenseExaminers->where('position', 'member')->values();
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="w-space-no fw-bold">{{ $defense->thesis->student->user->name ?? '-' }}</span>
                                            </div>
                                            <small class="text-muted">{{ $defense->thesis->student->nim ?? '-' }}</small><br>
                                            <small><em>{{ $defense->thesis->title }}</em></small>
                                        </td>
                                        <td>
                                            <strong>{{ $defense->defense_date ? \Carbon\Carbon::parse($defense->defense_date)->format('d M Y, H:i') : 'Belum Dijadwalkan' }}</strong><br>
                                            <span class="badge badge-light mt-1"><i class="fa fa-map-marker me-1"></i>{{ $defense->room ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($ketua)
                                                <span class="badge badge-primary light badge-sm mb-1" title="Ketua Penguji">
                                                    K: {{ $ketua->lecturer->user->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-warning light badge-sm mb-1">Ketua: Belum diplot</span>
                                            @endif
                                            
                                            @if($anggota->count() > 0)
                                                @foreach($anggota as $i => $ang)
                                                <br>
                                                <span class="badge badge-info light badge-sm mb-1" title="Anggota Penguji {{ $i+1 }}">
                                                    A{{ $i+1 }}: {{ $ang->lecturer->user->name }}
                                                </span>
                                                @endforeach
                                            @else
                                                <br>
                                                <span class="badge badge-warning light badge-sm mb-1">Anggota: Belum diplot</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info shadow-sm plot-defense-btn text-white" 
                                                    data-id="{{ $defense->id }}" 
                                                    data-name="{{ $defense->thesis->student->user->name ?? '' }}"
                                                    data-date="{{ $defense->defense_date ? \Carbon\Carbon::parse($defense->defense_date)->format('d M Y, H:i') : '-' }}"
                                                    data-p1="{{ $ketua ? $ketua->lecturer_id : '' }}"
                                                    data-p2="{{ $anggota->count() > 0 ? $anggota[0]->lecturer_id : '' }}"
                                                    data-p3="{{ $anggota->count() > 1 ? $anggota[1]->lecturer_id : '' }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#plotDefenseModal">
                                                <i class="fa fa-users me-1"></i> Atur Penguji Sidang
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada pendaftaran sidang skripsi.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plotting Seminar Modal -->
<div class="modal fade" id="plotModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atur Dosen Penguji Seminar Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kaprodi.examiners.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="proposal_seminar_id" id="plot_seminar_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa & Jadwal</label>
                        <input type="text" id="plot_student_name" class="form-control" readonly>
                        <input type="text" id="plot_seminar_date" class="form-control mt-1" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ketua Penguji <span class="text-danger">*</span></label>
                        <select name="lecturer_id_1" id="plot_lecturer_1" class="form-control default-select form-control-lg" required>
                            <option value="">-- Pilih Ketua Penguji --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anggota Penguji 1 <span class="text-danger">*</span></label>
                        <select name="lecturer_id_2" id="plot_lecturer_2" class="form-control default-select form-control-lg" required>
                            <option value="">-- Pilih Anggota Penguji 1 --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anggota Penguji 2 (Opsional)</label>
                        <select name="lecturer_id_3" id="plot_lecturer_3" class="form-control default-select form-control-lg">
                            <option value="">-- Tidak ada Anggota Penguji 2 --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
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

<!-- Plotting Defense Modal -->
<div class="modal fade" id="plotDefenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atur Dosen Penguji Sidang Skripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kaprodi.examiners.store_defense') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="thesis_defense_id" id="plot_defense_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa & Jadwal</label>
                        <input type="text" id="plot_defense_student_name" class="form-control" readonly>
                        <input type="text" id="plot_defense_date" class="form-control mt-1" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ketua Penguji <span class="text-danger">*</span></label>
                        <select name="lecturer_id_1" id="plot_defense_lecturer_1" class="form-control default-select form-control-lg" required>
                            <option value="">-- Pilih Ketua Penguji --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anggota Penguji 1 <span class="text-danger">*</span></label>
                        <select name="lecturer_id_2" id="plot_defense_lecturer_2" class="form-control default-select form-control-lg" required>
                            <option value="">-- Pilih Anggota Penguji 1 --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anggota Penguji 2 (Opsional)</label>
                        <select name="lecturer_id_3" id="plot_defense_lecturer_3" class="form-control default-select form-control-lg">
                            <option value="">-- Tidak ada Anggota Penguji 2 --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-info">Simpan Plotting Sidang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Plot Seminar
        const plotBtns = document.querySelectorAll('.plot-btn');
        plotBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('plot_seminar_id').value = this.getAttribute('data-id');
                document.getElementById('plot_student_name').value = this.getAttribute('data-name');
                document.getElementById('plot_seminar_date').value = this.getAttribute('data-date');
                
                const p1 = this.getAttribute('data-p1');
                const p2 = this.getAttribute('data-p2');
                const p3 = this.getAttribute('data-p3');
                
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
                
                if(p3) {
                    $('#plot_lecturer_3').val(p3).trigger('change');
                } else {
                    $('#plot_lecturer_3').val('').trigger('change');
                }
            });
        });

        // Plot Defense
        const plotDefenseBtns = document.querySelectorAll('.plot-defense-btn');
        plotDefenseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('plot_defense_id').value = this.getAttribute('data-id');
                document.getElementById('plot_defense_student_name').value = this.getAttribute('data-name');
                document.getElementById('plot_defense_date').value = this.getAttribute('data-date');
                
                const p1 = this.getAttribute('data-p1');
                const p2 = this.getAttribute('data-p2');
                const p3 = this.getAttribute('data-p3');
                
                // Set the select values
                if(p1) {
                    $('#plot_defense_lecturer_1').val(p1).trigger('change');
                } else {
                    $('#plot_defense_lecturer_1').val('').trigger('change');
                }
                
                if(p2) {
                    $('#plot_defense_lecturer_2').val(p2).trigger('change');
                } else {
                    $('#plot_defense_lecturer_2').val('').trigger('change');
                }
                
                if(p3) {
                    $('#plot_defense_lecturer_3').val(p3).trigger('change');
                } else {
                    $('#plot_defense_lecturer_3').val('').trigger('change');
                }
            });
        });
    });
</script>
@endsection
