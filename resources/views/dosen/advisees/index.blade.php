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
                                    @if($advisorRole && $advisorRole->position == 'supervisor_1')
                                        <span class="badge badge-primary">Pembimbing 1</span>
                                    @elseif($advisorRole && $advisorRole->position == 'supervisor_2')
                                        <span class="badge badge-info">Pembimbing 2</span>
                                    @else
                                        <span class="badge badge-secondary">Pembimbing</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $thesis->mentoringLogs->count() }} Log<br>
                                    <small class="text-success">{{ $thesis->mentoringLogs->where('status', 'approved')->count() }} Disetujui</small>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data mahasiswa bimbingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
