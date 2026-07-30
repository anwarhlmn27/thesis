@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-edit mr-2 text-white"></i>Revisi Sidang</h4>
                <p class="mb-0 text-white-50">Upload dokumen revisi skripsi dan pantau persetujuan penguji</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(!$defense)
                <div class="alert alert-warning text-center">Anda belum terdaftar untuk Sidang Skripsi.</div>
            @elseif($revisions->isEmpty() && !in_array($defense->status, ['passed', 'failed', 'completed']))
                <div class="alert alert-warning text-center">Anda belum melaksanakan Sidang Skripsi atau status sidang belum dinyatakan selesai. Anda tidak dapat mengunggah revisi.</div>
            @elseif($revisions->isEmpty())
                <div class="alert alert-secondary text-center">Tim penguji belum menginput data perbaikan revisi untuk Anda.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Dosen Penguji</th>
                                <th>Catatan Revisi</th>
                                <th>Status Persetujuan Revisi</th>
                                <th>Dokumen Revisi Anda</th>
                                <th>Aksi (Upload)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revisions as $revision)
                                <tr>
                                    <td>
                                        <strong>{{ $revision->lecturer->user->name }}</strong>
                                    </td>
                                    <td>
                                        {!! nl2br(e($revision->description)) !!}
                                    </td>
                                    <td>
                                        @if($revision->is_approved)
                                            <span class="badge badge-success"><i class="fa fa-check"></i> Revisi Disetujui</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu Persetujuan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($revision->revision_file_path)
                                            <a href="{{ Storage::url($revision->revision_file_path) }}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-download"></i> Lihat File Revisi</a>
                                        @else
                                            <span class="text-danger">Belum diunggah</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$revision->is_approved)
                                            <form action="{{ route('student.revisions.store') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                                @csrf
                                                <input type="hidden" name="revision_id" value="{{ $revision->id }}">
                                                <input type="file" name="revision_file" class="form-control form-control-sm me-2" accept=".pdf" required style="max-width: 200px;">
                                                <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Telah Disetujui</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @php
                    $allApproved = $revisions->every(function($r) { return $r->is_approved; });
                @endphp
                @if($allApproved)
                    <div class="alert alert-success mt-4">
                        <h4><i class="fa fa-star text-warning"></i> Selamat!</h4>
                        <p class="mb-0">Semua revisi dari tim penguji telah disetujui. Silakan tunggu informasi penerbitan SK Yudisium dari pihak BAAK.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
