@extends('layout.app')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Data Staf</h4>
            <p class="mb-0">Kelola data staf BAAK, Finance, Library (Perpustakaan)</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus me-2"></i>Tambah Staf
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Staf</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:80px;"><strong>#</strong></th>
                                <th><strong>NIP</strong></th>
                                <th><strong>NAMA STAF</strong></th>
                                <th><strong>DEPARTEMEN / UNIT</strong></th>
                                <th><strong>EMAIL</strong></th>
                                <th><strong>NO. TELEPON</strong></th>
                                <th><strong>AKSI</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $index => $staff)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>{{ $staff->nip }}</td>
                                <td>{{ $staff->user->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($staff->department) {
                                            'BAAK' => 'badge-info',
                                            'Finance' => 'badge-success',
                                            'Library' => 'badge-warning',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $staff->department }}
                                    </span>
                                </td>
                                <td>{{ $staff->user->email ?? '-' }}</td>
                                <td>{{ $staff->phone ?? '-' }}</td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary shadow btn-xs sharp me-1 edit-btn" 
                                                data-id="{{ $staff->id }}" 
                                                data-name="{{ $staff->user->name ?? '' }}" 
                                                data-email="{{ $staff->user->email ?? '' }}"
                                                data-nip="{{ $staff->nip }}"
                                                data-department="{{ $staff->department }}"
                                                data-phone="{{ $staff->phone ?? '' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirmDelete(event, this)" class="d-inline" data-confirm-message="Apakah Anda yakin ingin menghapus staf ini?">
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
                                <td colspan="7" class="text-center">Belum ada data staf.</td>
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
                <h5 class="modal-title">Tambah Staf Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Departemen / Unit Kerja</label>
                        <select name="department" class="form-control" required>
                            <option value="">-- Pilih Departemen --</option>
                            <option value="BAAK">BAAK (Administrasi Akademik)</option>
                            <option value="Finance">Finance (Keuangan)</option>
                            <option value="Library">Library (Perpustakaan)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">No. Telepon / WA (Opsional)</label>
                        <input type="text" name="phone" class="form-control" placeholder="Masukkan nomor telepon">
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
                <h5 class="modal-title">Edit Data Staf</h5>
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
                        <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" id="edit_nip" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Departemen / Unit Kerja</label>
                        <select name="department" id="edit_department" class="form-control" required>
                            <option value="BAAK">BAAK (Administrasi Akademik)</option>
                            <option value="Finance">Finance (Keuangan)</option>
                            <option value="Library">Library (Perpustakaan)</option>
                            
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">No. Telepon / WA (Opsional)</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
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
                const nip = this.getAttribute('data-nip');
                const department = this.getAttribute('data-department');
                const phone = this.getAttribute('data-phone');
                
                document.getElementById('editForm').action = `/admin/staff/${id}`;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_nip').value = nip;
                document.getElementById('edit_department').value = department;
                document.getElementById('edit_phone').value = phone;
            });
        });
    });
</script>
@endsection
