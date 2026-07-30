@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-users mr-2 text-white"></i>Daftar Mahasiswa Bimbingan</h4>
                <p class="mb-0 text-white-50">Monitoring progress skripsi mahasiswa bimbingan Anda</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th>Status Pembimbing</th>
                            <th>Progress (Log)</th>
                            <th>Status Skripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($theses as $index => $thesis)
                            @php
                                $advisorRole = $thesis->thesisAdvisors->where('lecturer_id', auth()->user()->lecturer->id)->first();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $thesis->student->user->name }}</strong><br>
                                    <small class="text-muted">NIM: {{ $thesis->student->nim }}</small>
                                </td>
                                <td>{{ $thesis->title }}</td>
                                <td>
                                    @if($advisorRole && $advisorRole->type == 'primary')
                                        <span class="badge badge-primary">Pembimbing 1</span>
                                    @elseif($advisorRole && $advisorRole->type == 'secondary')
                                        <span class="badge badge-info">Pembimbing 2</span>
                                    @else
                                        <span class="badge badge-secondary">Pembimbing</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $myLogs = $advisorRole ? $thesis->mentoringLogs->where('thesis_advisor_id', $advisorRole->id) : collect();
                                    @endphp
                                    {{ $myLogs->count() }} Log<br>
                                    <small class="text-success">{{ $myLogs->where('status', 'approved')->count() }} Disetujui</small>
                                </td>
                                <td>
                                    @if($thesis->status == 'active')
                                        <span class="badge badge-warning">Proses Bimbingan</span>
                                    @elseif($thesis->status == 'completed')
                                        <span class="badge badge-success">Lulus</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($thesis->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('dosen.mentoring-logs.index') }}" class="btn btn-sm btn-primary">
                                            <i class="la la-check-circle"></i> Review Log
                                        </a>
                                        @if($myLogs->where('status', 'approved')->count() >= 10)
                                            @if($advisorRole->is_approved_for_defense)
                                                <form action="{{ route('dosen.advisees.approve', $thesis->id) }}" method="POST" onsubmit="confirmAction(event, this)" data-confirm-message="Batalkan ACC sidang skripsi mahasiswa ini?">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan ACC Sidang</button>
                                                </form>
                                            @else
                                                <form action="{{ route('dosen.advisees.approve', $thesis->id) }}" method="POST" onsubmit="confirmAction(event, this)" data-confirm-message="ACC kelayakan sidang mahasiswa ini? Pastikan bimbingan sudah cukup.">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="la la-check"></i> ACC Sidang</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data mahasiswa bimbingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
