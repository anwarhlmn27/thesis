@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="row page-titles mx-0 mb-4 text-white rounded p-3 align-items-center shadow-sm" style="background: linear-gradient(90deg, #fd7e14 0%, #ffc107 100%);">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4 class="text-white mb-1"><i class="la la-book-reader mr-2"></i>Dashboard Staf Perpustakaan</h4>
                <p class="mb-0 text-white-50">Verifikasi Bebas Pustaka & Penyerahan Naskah Jurnal/Skripsi</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <div class="d-flex align-items-center">
                <span class="mr-3 text-white">Role Aktif:</span>
                <span class="badge badge-light text-warning border-0 px-3 py-2" style="font-size: 14px; font-weight: 600;">Staf Perpustakaan</span>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Menunggu Bebas Pustaka</h5>
                        <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['pending_count']) }}</h2>
                        <small class="text-white-50">Permohonan Clearance / Tanggungan</small>
                    </div>
                    <div>
                        <i class="la la-exclamation-circle text-white" style="font-size: 48px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Naskah / Jurnal Masuk</h5>
                        <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['final_submissions']) }}</h2>
                        <small class="text-white-50">Penyerahan Skripsi & Publikasi</small>
                    </div>
                    <div>
                        <i class="la la-file-upload text-white" style="font-size: 48px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100 mb-0">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white mb-1">Clearance Selesai</h5>
                        <h2 class="text-white font-weight-bold mb-1">{{ number_format($stats['clear_count']) }}</h2>
                        <small class="text-white-50">{{ $stats['clear_percentage'] }}% dari total {{ number_format($stats['total_students']) }} mahasiswa</small>
                    </div>
                    <div>
                        <i class="la la-check-circle text-white" style="font-size: 48px; opacity: 0.85;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="card-title mb-1 font-weight-bold"><i class="la la-book-reader mr-2 text-warning"></i>Portal Validasi Perpustakaan</h5>
                        <p class="text-muted mb-0">Kelola status bebas tanggungan perpustakaan dan cek kelengkapan penyerahan naskah skripsi/jurnal mahasiswa.</p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <a href="{{ route('library.clearance.index') }}" class="btn btn-warning text-white"><i class="la la-check-square mr-1"></i> Buka Portal Clearance Perpustakaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Antrean Verifikasi Perpustakaan (Actual Data) -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pb-0 d-flex flex-wrap justify-content-between align-items-center">
                    <h4 class="card-title mb-2 mb-md-0 font-weight-bold">Antrean Verifikasi Perpustakaan</h4>
                    <ul class="nav nav-pills" id="perpustakaanTab" role="tablist">
                        <li class="nav-item mr-1" role="presentation">
                            <a class="nav-link active btn-sm py-2 px-3" id="pills-clearance-tab" data-toggle="pill" data-bs-toggle="pill" href="#pills-clearance" data-bs-target="#pills-clearance" role="tab" aria-selected="true">
                                <i class="la la-clock-o mr-1"></i> Menunggu Bebas Pustaka ({{ $pendingStudents->count() }})
                            </a>
                        </li>
                        <li class="nav-item mr-1" role="presentation">
                            <a class="nav-link btn-sm py-2 px-3" id="pills-jurnal-tab" data-toggle="pill" data-bs-toggle="pill" href="#pills-jurnal" data-bs-target="#pills-jurnal" role="tab" aria-selected="false">
                                <i class="la la-file-alt mr-1"></i> Penyerahan Jurnal/Skripsi ({{ $finalSubmissions->count() }})
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn-sm py-2 px-3" id="pills-cleared-tab" data-toggle="pill" data-bs-toggle="pill" href="#pills-cleared" data-bs-target="#pills-cleared" role="tab" aria-selected="false">
                                <i class="la la-check-circle mr-1"></i> Sudah Bebas Pustaka ({{ $clearedStudents->count() }})
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="perpustakaanTabContent">
                        <!-- Tab 1: Antrean Menunggu Bebas Pustaka -->
                        <div class="tab-pane fade show active" id="pills-clearance" role="tabpanel">
                            @if($pendingStudents->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Program Studi</th>
                                            <th>Semester</th>
                                            <th>Status Bebas Pustaka</th>
                                            <th>Skripsi Terdaftar</th>
                                            <th>Keputusan / Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingStudents as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-light text-warning rounded-circle mr-2 text-center" style="width: 35px; height: 35px; line-height: 35px; font-weight: bold; font-size: 13px;">
                                                        {{ strtoupper(substr($student->user?->name ?? 'M', 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">{{ $student->user->name ?? '-' }}</strong><br>
                                                        <small class="text-muted">NIM: {{ $student->nim }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $student->prodi }}</td>
                                            <td>Semester {{ $student->semester }}</td>
                                            <td>
                                                <span class="badge badge-light text-danger"><i class="fa fa-times-circle mr-1"></i> Belum Bebas</span>
                                            </td>
                                            <td>
                                                @if($student->theses && $student->theses->isNotEmpty())
                                                    <span class="text-dark font-weight-500">{{ Str::limit($student->theses->last()->title, 40) }}</span>
                                                @else
                                                    <span class="text-muted small">Belum mengajukan skripsi</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('library.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmAction(event, this)" data-confirm-message="Tandai mahasiswa {{ $student->user->name ?? $student->nim }} telah bebas tanggungan perpustakaan?" data-confirm-btn="Ya, Setujui Bebas Pustaka">
                                                    @csrf
                                                    <input type="hidden" name="is_library_clear" value="1">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fa fa-check mr-1"></i> Bebas Pustaka
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="la la-check-circle text-success" style="font-size: 50px;"></i>
                                <h5 class="mt-3 text-dark font-weight-bold">Tidak Ada Antrean Bebas Pustaka</h5>
                                <p class="text-muted mb-0">Semua mahasiswa telah memiliki status bebas perpustakaan.</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Tab 2: Penyerahan Naskah / Jurnal Final Skripsi -->
                        <div class="tab-pane fade" id="pills-jurnal" role="tabpanel">
                            @if($finalSubmissions->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Judul Skripsi</th>
                                            <th>Status Skripsi</th>
                                            <th>Dokumen Final</th>
                                            <th>Tgl Penyerahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($finalSubmissions as $thesis)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-light text-info rounded-circle mr-2 text-center" style="width: 35px; height: 35px; line-height: 35px; font-weight: bold; font-size: 13px;">
                                                        {{ strtoupper(substr($thesis->student->user?->name ?? 'M', 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">{{ $thesis->student->user->name ?? '-' }}</strong><br>
                                                        <small class="text-muted">NIM: {{ $thesis->student->nim ?? '-' }} | {{ $thesis->student->prodi ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $thesis->title }}</strong>
                                                @if($thesis->abstract)
                                                    <p class="text-muted small mb-0 mt-1">{{ Str::limit($thesis->abstract, 60) }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $thesis->status)) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $finalFileUrl = str_starts_with($thesis->final_file_path, 'http') 
                                                        ? $thesis->final_file_path 
                                                        : (str_starts_with($thesis->final_file_path, 'storage/') ? asset($thesis->final_file_path) : asset('storage/' . $thesis->final_file_path));
                                                @endphp
                                                @if(str_ends_with(strtolower($thesis->final_file_path), '.pdf'))
                                                    <button type="button" onclick="previewPdf('{{ $finalFileUrl }}', 'Naskah Final - {{ addslashes($thesis->title) }}')" class="btn btn-xs btn-info">
                                                        <i class="fa fa-file-pdf mr-1"></i> Preview PDF
                                                    </button>
                                                @else
                                                    <a href="{{ $finalFileUrl }}" target="_blank" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-download mr-1"></i> Unduh File
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $thesis->updated_at ? $thesis->updated_at->format('d M Y H:i') : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="la la-file-upload text-muted" style="font-size: 50px;"></i>
                                <h5 class="mt-3 text-dark font-weight-bold">Belum Ada Penyerahan Naskah Final</h5>
                                <p class="text-muted mb-0">Mahasiswa yang telah mengunggah naskah final skripsi / artikel jurnal akan otomatis muncul di sini.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Tab 3: Mahasiswa yang Sudah Bebas Pustaka -->
                        <div class="tab-pane fade" id="pills-cleared" role="tabpanel">
                            @if($clearedStudents->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>Program Studi</th>
                                            <th>Semester</th>
                                            <th>Status Bebas Pustaka</th>
                                            <th>Tgl Diperbarui</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clearedStudents as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-light text-success rounded-circle mr-2 text-center" style="width: 35px; height: 35px; line-height: 35px; font-weight: bold; font-size: 13px;">
                                                        {{ strtoupper(substr($student->user?->name ?? 'M', 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">{{ $student->user->name ?? '-' }}</strong><br>
                                                        <small class="text-muted">NIM: {{ $student->nim }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $student->prodi }}</td>
                                            <td>Semester {{ $student->semester }}</td>
                                            <td>
                                                <span class="badge badge-light text-success"><i class="fa fa-check-circle mr-1"></i> Bebas Perpustakaan</span>
                                            </td>
                                            <td>{{ $student->updated_at ? $student->updated_at->format('d M Y H:i') : '-' }}</td>
                                            <td>
                                                <form action="{{ route('library.clearance.update_student', $student->id) }}" method="POST" onsubmit="confirmAction(event, this)" data-confirm-message="Batalkan status bebas perpustakaan untuk mahasiswa {{ $student->user->name ?? $student->nim }}?" data-confirm-btn="Ya, Batalkan!">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa fa-undo mr-1"></i> Batalkan Status
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="la la-hourglass-half text-warning" style="font-size: 50px;"></i>
                                <h5 class="mt-3 text-dark font-weight-bold">Belum Ada Mahasiswa Bebas Pustaka</h5>
                                <p class="text-muted mb-0">Silakan lakukan validasi pada tab 'Menunggu Bebas Pustaka'.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
