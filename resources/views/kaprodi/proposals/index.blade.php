@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Persetujuan Proposal Skripsi</h4>
            <p class="mb-0">Daftar pengajuan proposal yang membutuhkan persetujuan Kaprodi</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Proposal</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Proposal Skripsi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>#</strong></th>
                                <th><strong>MAHASISWA</strong></th>
                                <th><strong>JUDUL PROPOSAL</strong></th>
                                <th><strong>TANGGAL PENGAJUAN</strong></th>
                                <th><strong>STATUS BAAK</strong></th>
                                <th><strong>PERSETUJUAN KAPRODI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposals as $index => $proposal)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="w-space-no">{{ $proposal->thesis->student->user->name ?? '-' }}</span>
                                    </div>
                                    <small class="text-muted">{{ $proposal->thesis->student->nim ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $proposal->thesis->title ?? '-' }}</strong>
                                    @if($proposal->proposal_file_path)
                                    <br>
                                    <a href="javascript:void(0)" onclick="viewPdf('{{ Storage::url($proposal->proposal_file_path) }}')" class="badge badge-xs badge-info mt-1"><i class="fa fa-eye me-1"></i>Lihat</a>
                                    <a href="{{ Storage::url($proposal->proposal_file_path) }}" target="_blank" class="badge badge-xs badge-primary mt-1"><i class="fa fa-download me-1"></i>Unduh</a>
                                    @endif
                                </td>
                                <td>{{ $proposal->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($proposal->is_baak_approved === true)
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($proposal->is_baak_approved === false && $proposal->is_baak_approved !== null)
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($proposal->is_kaprodi_approved === true)
                                        <span class="badge badge-success mb-2"><i class="fa fa-check me-1"></i>Disetujui</span>
                                        <br>
                                        <form action="{{ route('kaprodi.proposals.approve', $proposal->id) }}" method="POST" onsubmit="return confirmKaprodi(event, this, 'Batal Setuju')" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="is_kaprodi_approved" value="0">
                                            <button type="submit" class="btn btn-xs btn-outline-danger shadow-sm">Batalkan</button>
                                        </form>
                                    @else
                                        <form action="{{ route('kaprodi.proposals.approve', $proposal->id) }}" method="POST" onsubmit="return confirmKaprodi(event, this, 'Setuju')" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="is_kaprodi_approved" value="1">
                                            <button type="submit" class="btn btn-sm btn-primary shadow-sm"><i class="fa fa-check me-1"></i>Setujui Proposal</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pengajuan proposal saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PDF Viewer Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pratinjau Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfFrame" src="" style="width: 100%; height: 75vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function viewPdf(url) {
        document.getElementById('pdfFrame').src = url;
        var myModal = new bootstrap.Modal(document.getElementById('pdfModal'));
        myModal.show();
    }

    function confirmKaprodi(event, form, actionName) {
        event.preventDefault();
        
        let confirmText = actionName === 'Setuju' 
            ? "Apakah Anda yakin ingin menyetujui proposal ini?" 
            : "Apakah Anda yakin ingin membatalkan persetujuan proposal ini?";
            
        let confirmColor = actionName === 'Setuju' ? '#28a745' : '#dc3545';
        
        Swal.fire({
            title: 'Konfirmasi',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endsection
