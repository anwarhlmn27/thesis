@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-check-circle mr-2 text-white"></i>Persetujuan Revisi Sidang</h4>
                <p class="mb-0 text-white-50">Review dan berikan persetujuan untuk dokumen revisi pasca sidang</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th>Dokumen Revisi</th>
                            <th>Tanggal Upload</th>
                            <th>Status Persetujuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revisions as $revision)
                            <tr>
                                <td>
                                    <strong>{{ $revision->thesisDefense->thesis->student->user->name }}</strong><br>
                                    <small class="text-muted">NIM: {{ $revision->thesisDefense->thesis->student->nim }}</small>
                                </td>
                                <td>{{ $revision->thesisDefense->thesis->title }}</td>
                                <td>
                                    @if($revision->file_path)
                                        <a href="{{ Storage::url($revision->file_path) }}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-download"></i> Lihat Revisi</a>
                                    @else
                                        <span class="text-danger">Belum diunggah</span>
                                    @endif
                                </td>
                                <td>{{ $revision->updated_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($revision->is_approved)
                                        <span class="badge badge-success"><i class="fa fa-check"></i> Disetujui</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$revision->is_approved)
                                        <form action="{{ route('dosen.revisions.approve', $revision->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="is_approved" value="1">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin menyetujui revisi ini?')">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Telah Disetujui</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada revisi yang perlu disetujui.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
