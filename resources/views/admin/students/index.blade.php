@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Data Mahasiswa</h4>
            <p class="mb-0">Kelola informasi mahasiswa dan status prasyarat skripsi</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Mahasiswa
        </button>
    </div>
</div>





<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Mahasiswa</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>NIM</strong></th>
                                <th><strong>NAMA</strong></th>
                                <th><strong>PRODI</strong></th>
                                <th><strong>SEM.</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>{{ $student->nim }}</td>
                                <td>{{ $student->user->name ?? '-' }}</td>
                                <td>{{ $student->prodi }}</td>
                                <td>{{ $student->semester }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge {{ $student->is_paid ? 'badge-success' : 'badge-danger' }}">
                                            Pembayaran: {{ $student->is_paid ? 'Lunas' : 'Belum' }}
                                        </span>
                                        <span class="badge {{ $student->is_library_clear ? 'badge-success' : 'badge-danger' }}">
                                            Bebas Pustaka: {{ $student->is_library_clear ? 'Clear' : 'Belum' }}
                                        </span>
                                        <span class="badge {{ $student->is_coursework_completed ? 'badge-success' : 'badge-danger' }}">
                                            Mata Kuliah: {{ $student->is_coursework_completed ? 'Lulus' : 'Belum' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex mt-2">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $student->id }}" 
                                                data-name="{{ $student->user->name ?? '' }}" 
                                                data-email="{{ $student->user->email ?? '' }}"
                                                data-nim="{{ $student->nim }}"
                                                data-prodi="{{ $student->prodi }}"
                                                data-semester="{{ $student->semester }}"
                                                data-is-paid="{{ $student->is_paid ? '1' : '0' }}"
                                                data-is-library-clear="{{ $student->is_library_clear ? '1' : '0' }}"
                                                data-is-coursework-completed="{{ $student->is_coursework_completed ? '1' : '0' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus mahasiswa ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data mahasiswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email mahasiswa" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="prodi" class="form-control" placeholder="Masukkan program studi" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control" placeholder="Masukkan semester aktif" required min="1">
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_paid" class="form-check-input" id="add_is_paid" value="1">
                            <label class="form-check-label" for="add_is_paid">Sudah Membayar Uang Kuliah</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_library_clear" class="form-check-input" id="add_is_library_clear" value="1">
                            <label class="form-check-label" for="add_is_library_clear">Bebas Tunggakan Pustaka</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_coursework_completed" class="form-check-input" id="add_is_coursework_completed" value="1">
                            <label class="form-check-label" for="add_is_coursework_completed">SKS/Mata Kuliah Prasyarat Selesai</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" id="edit_nim" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="prodi" id="edit_prodi" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" id="edit_semester" class="form-control" required min="1">
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_paid" class="form-check-input" id="edit_is_paid" value="1">
                            <label class="form-check-label" for="edit_is_paid">Sudah Membayar Uang Kuliah</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_library_clear" class="form-check-input" id="edit_is_library_clear" value="1">
                            <label class="form-check-label" for="edit_is_library_clear">Bebas Tunggakan Pustaka</label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" name="is_coursework_completed" class="form-check-input" id="edit_is_coursework_completed" value="1">
                            <label class="form-check-label" for="edit_is_coursework_completed">SKS/Mata Kuliah Prasyarat Selesai</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editBtns = document.querySelectorAll('.edit-btn');
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                const nim = this.getAttribute('data-nim');
                const prodi = this.getAttribute('data-prodi');
                const semester = this.getAttribute('data-semester');
                const isPaid = this.getAttribute('data-is-paid');
                const isLibClear = this.getAttribute('data-is-library-clear');
                const isCourseDone = this.getAttribute('data-is-coursework-completed');
                
                document.getElementById('editForm').action = `/admin/students/${id}`;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_nim').value = nim;
                document.getElementById('edit_prodi').value = prodi;
                document.getElementById('edit_semester').value = semester;
                
                document.getElementById('edit_is_paid').checked = isPaid === '1';
                document.getElementById('edit_is_library_clear').checked = isLibClear === '1';
                document.getElementById('edit_is_coursework_completed').checked = isCourseDone === '1';
            });
        });
    });
</script>
@endsection
