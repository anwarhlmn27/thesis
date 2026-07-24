@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-file-alt mr-2 text-white"></i>Proposal Skripsi</h4>
                <p class="mb-0 text-white-50">Upload proposal dan pantau status kelayakan</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Upload Proposal</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.proposal.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Judul Skripsi</label>
                            <input type="text" name="title" class="form-control" value="{{ $thesis->title ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Abstrak Singkat</label>
                            <textarea name="abstract" class="form-control" rows="4">{{ $thesis->abstract ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Proposal (PDF, Max 10MB)</label>
                            @if($thesis && $thesis->latestProposal)
                                <input type="file" name="proposal_file" class="form-control" accept=".pdf">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file proposal Anda.</small>
                            @else
                                <input type="file" name="proposal_file" class="form-control" accept=".pdf" required>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">Upload / Update Proposal</button>
                    </form>
                    
                    @if($thesis && $thesis->latestProposal)
                    <form action="{{ route('student.proposal.destroy') }}" method="POST" 
                        @if(isset($seminar) && $seminar)
                            onsubmit="event.preventDefault(); Swal.fire('Gagal', 'tidak bisa menghapus proposal karena sudah di tentukan jadwal seminar proposal', 'error');"
                        @else
                            onsubmit="confirmDelete(event, this)" data-confirm-message="Apakah Anda yakin ingin membatalkan/menghapus pengajuan proposal ini? Semua data terkait akan terhapus."
                        @endif
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100"><i class="fa fa-trash"></i> Batalkan & Hapus Proposal</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Status Proposal</h5>
                </div>
                <div class="card-body">
                    @if($thesis && $thesis->latestProposal)
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">File Proposal</th>
                                <td><a href="{{ Storage::url($thesis->latestProposal->proposal_file_path) }}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-file-pdf"></i> Lihat File</a></td>
                            </tr>
                            <tr>
                                <th>Validasi Akademik (BAAK)</th>
                                <td>
                                    @if($thesis->latestProposal->is_baak_approved)
                                        <span class="badge badge-success"><i class="fa fa-check"></i> Disetujui</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Validasi Keuangan (Finance)</th>
                                <td>
                                    @if($thesis->latestProposal->is_finance_approved)
                                        <span class="badge badge-success"><i class="fa fa-check"></i> Disetujui</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Validasi Kaprodi</th>
                                <td>
                                    @if($thesis->latestProposal->is_kaprodi_approved)
                                        <span class="badge badge-success"><i class="fa fa-check"></i> Disetujui</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-secondary text-center">Belum ada proposal yang diunggah.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Seminar -->
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Jadwal Seminar Proposal</h5>
                </div>
                <div class="card-body">
                    @if($seminar)
                        <div class="alert alert-info">
                            <h5><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($seminar->scheduled_at)->format('d F Y, H:i') }} WIB</h5>
                            <p class="mb-0"><i class="fa fa-map-marker"></i> Ruangan: <strong>{{ $seminar->room }}</strong></p>
                        </div>
                        <h6>Tim Penguji:</h6>
                        <ul>
                            @foreach($seminar->proposalExaminers as $examiner)
                                <li>{{ $examiner->lecturer->user->name }} ({{ $examiner->position === 'chairman' ? 'Ketua Penguji' : 'Anggota Penguji' }})</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-secondary text-center">Belum ada jadwal seminar. Silakan tunggu penetapan dari BAAK setelah proposal Anda disetujui.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
