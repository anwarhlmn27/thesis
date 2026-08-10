@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-check-circle mr-2 text-white"></i>Portal BAAK</h4>
                <p class="mb-0 text-white-50">Validasi kelayakan akademik dan SKS</p>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-proposal-tab" data-bs-toggle="pill" data-bs-target="#pills-proposal" type="button" role="tab"><i class="la la-file-alt mr-1"></i> Validasi Kelayakan Seminar Proposal</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-akademik-tab" data-bs-toggle="pill" data-bs-target="#pills-akademik" type="button" role="tab"><i class="la la-graduation-cap mr-1"></i> Validasi Kelayakan Sidang Skripsi (Cek SKS)</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <!-- Tab 1: Validasi Proposal Seminar -->
        <div class="tab-pane fade show active" id="pills-proposal" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul Proposal</th>
                                    <th>File Proposal</th>
                                    <th>Status Validasi BAAK</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proposals as $proposal)
                                <tr>
                                    <td>
                                        <strong>{{ $proposal->thesis->student->user->name ?? '-' }}</strong><br>
                                        <small class="text-muted">NIM: {{ $proposal->thesis->student->nim ?? '-' }} | {{ $proposal->thesis->student->prodi ?? '-' }}</small>
                                    </td>
                                    <td>{{ $proposal->thesis->title ?? '-' }}</td>
                                    <td>
                                        @if($proposal->proposal_file_path)
                                            <a href="{{ Storage::url($proposal->proposal_file_path) }}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-file-pdf mr-1"></i> Lihat Draf</a>
                                        @else
                                            <span class="text-muted small">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($proposal->is_baak_approved)
                                            <span class="badge badge-success"><i class="fa fa-check"></i> Layak Seminar (Disetujui)</span>
                                            @if($proposal->baak_approved_at)
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($proposal->baak_approved_at)->format('d M Y H:i') }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-warning">Menunggu Validasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($proposal->is_baak_approved)
                                            <form action="{{ route('baak.clearance.update_proposal', $proposal->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Batalkan validasi kelayakan seminar proposal ini?">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Validasi</button>
                                            </form>
                                        @else
                                            <form action="{{ route('baak.clearance.update_proposal', $proposal->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Tandai proposal ini layak untuk diseminarkan?">
                                                @csrf
                                                <input type="hidden" name="is_baak_approved" value="1">
                                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Validasi Proposal</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan proposal mahasiswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Validasi SKS Sidang Skripsi -->
        <div class="tab-pane fade" id="pills-akademik" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Prodi / Smt</th>
                                    <th>Status Kelayakan Akademik (SKS)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>
                                        <strong>{{ $student->user->name ?? '-' }}</strong><br>
                                        <small class="text-muted">NIM: {{ $student->nim }}</small>
                                    </td>
                                    <td>{{ $student->prodi }} / Semester {{ $student->semester }}</td>
                                    <td>
                                        @if($student->is_coursework_completed)
                                            <span class="badge badge-success"><i class="fa fa-check"></i> SKS Memenuhi Syarat</span>
                                        @else
                                            <span class="badge badge-danger">Belum Memenuhi Syarat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->is_coursework_completed)
                                            <form action="{{ route('baak.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Batalkan status layak akademik mahasiswa ini?">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Status</button>
                                            </form>
                                        @else
                                            <form action="{{ route('baak.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmDelete(event, this)" data-confirm-message="Tandai mahasiswa ini memenuhi syarat SKS?">
                                                @csrf
                                                <input type="hidden" name="is_coursework_completed" value="1">
                                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Validasi SKS</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data mahasiswa.</td>
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
@endsection
