@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mx-0 mb-4 bg-primary text-white rounded p-4 align-items-center shadow-sm">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-book-reader mr-2 text-white"></i>Portal Perpustakaan</h4>
                <p class="mb-0 text-white-50">Validasi bebas tanggungan perpustakaan</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Prodi / Smt</th>
                            <th>Status Bebas Perpustakaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <strong>{{ $student->user->name }}</strong><br>
                                <small class="text-muted">NIM: {{ $student->nim }}</small>
                            </td>
                            <td>{{ $student->prodi }} / {{ $student->semester }}</td>
                            <td>
                                @if($student->is_library_clear)
                                    <span class="badge badge-success"><i class="fa fa-check"></i> Bebas Perpus</span>
                                @else
                                    <span class="badge badge-danger">Ada Tanggungan</span>
                                @endif
                            </td>
                            <td>
                                @if($student->is_library_clear)
                                    <form action="{{ route('library.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmAction(event, this)" data-confirm-message="Batalkan status bebas perpustakaan mahasiswa ini?" data-confirm-btn="Ya, Batalkan!">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Status</button>
                                    </form>
                                @else
                                    <form action="{{ route('library.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmAction(event, this)" data-confirm-message="Tandai mahasiswa ini bebas dari tanggungan perpustakaan?" data-confirm-btn="Ya, Validasi!">
                                        @csrf
                                        <input type="hidden" name="is_library_clear" value="1">
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Validasi Bebas Perpus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
