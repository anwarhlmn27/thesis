<div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a href="{{ route('dashboard') }}" aria-expanded="false">
							<i class="la la-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                    </li>
                    
                    @if(auth()->user()->role === 'student')
                    <li class="nav-label">Portal Mahasiswa</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-file-alt"></i>
							<span class="nav-text">Proposal Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('student.proposal.index') }}">Upload Proposal & Jadwal</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-book"></i>
							<span class="nav-text">Bimbingan</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('student.mentoring-logs.index') }}">Input & Log Bimbingan</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-graduation-cap"></i>
							<span class="nav-text">Sidang & Revisi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('student.defenses.index') }}">Daftar & Jadwal Sidang</a></li>
                            <li><a href="{{ route('student.revisions.index') }}">Upload Revisi</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->role === 'lecturer')
                    <li class="nav-label">Portal Dosen</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-users"></i>
							<span class="nav-text">Bimbingan Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('dosen.advisees.index') }}">Daftar Mahasiswa</a></li>
                            <li><a href="{{ route('dosen.mentoring-logs.index') }}">Persetujuan Log Bimbingan</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-gavel"></i>
							<span class="nav-text">Ujian & Sidang</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('dosen.exams.index') }}">Jadwal Ujian (Seminar/Sidang)</a></li>
                            <li><a href="{{ route('dosen.revisions.index') }}">Persetujuan Revisi</a></li>
                        </ul>
                    </li>
                        @if(auth()->user()->lecturer && auth()->user()->lecturer->is_kaprodi)
                        <li class="nav-label">Portal Kaprodi</li>
                        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                                <i class="la la-sitemap"></i>
                                <span class="nav-text">Manajemen Skripsi</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('kaprodi.proposals.index') }}">Persetujuan Proposal</a></li>
                                <li><a href="{{ route('kaprodi.advisors.index') }}">Plotting Pembimbing</a></li>
                                <li><a href="{{ route('kaprodi.examiners.index') }}">Plotting Penguji</a></li>
                            </ul>
                        </li>
                        @endif
                    @endif

                    @if(auth()->user()->role === 'staff_finance')
                    <li class="nav-label">Portal Finance</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-money-bill-wave"></i>
							<span class="nav-text">Validasi Keuangan</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('finance.clearance.index') }}">Validasi Pembayaran UKT & Seminar</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->role === 'staff_library')
                    <li class="nav-label">Portal Perpustakaan</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-book-reader"></i>
							<span class="nav-text">Validasi Perpustakaan</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('library.clearance.index') }}">Bebas Tanggungan Perpus</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->role === 'staff_baak')
                    <li class="nav-label">Portal BAAK</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-check-circle"></i>
							<span class="nav-text">Validasi Akademik</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('baak.clearance.index') }}">Proposal & Skripsi</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-calendar"></i>
							<span class="nav-text">Penjadwalan Ujian</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('proposal-seminars.index') }}">Seminar Proposal</a></li>
                            <li><a href="{{ route('thesis-defenses.index') }}">Sidang Skripsi</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-graduation-cap"></i>
							<span class="nav-text">Kelulusan</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('yudisiums.index') }}">SK Yudisium</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <li class="nav-label">Data Master & Pengguna</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-users"></i>
							<span class="nav-text">Kelola Pengguna</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('users.index') }}">Akun Login (Users)</a></li>
                            <li><a href="{{ route('staff.index') }}">Data Staff</a></li>
                            <li><a href="{{ route('lecturers.index') }}">Data Dosen</a></li>
                            <li><a href="{{ route('students.index') }}">Data Mahasiswa</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Tahap 1: Proposal Skripsi</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-file-text"></i>
							<span class="nav-text">Proposal & Seminar</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('theses.index') }}">1. Data Master Skripsi</a></li>
                            <li><a href="{{ route('thesis-proposals.index') }}">2. Upload & Eligibility 3 Pihak</a></li>
                            <li><a href="{{ route('proposal-seminars.index') }}">3. Jadwal Seminar Proposal</a></li>
                            <li><a href="{{ route('proposal-examiners.index') }}">4. Tim Penguji Seminar</a></li>
                            <li><a href="{{ route('proposal-comments.index') }}">5. Status Layak & Catatan Penguji</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Tahap 2: Bimbingan Skripsi</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-book"></i>
							<span class="nav-text">Proses Bimbingan</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('thesis-advisors.index') }}">1. Pembimbing Skripsi</a></li>
                            <li><a href="{{ route('mentoring-logs.index') }}">2. Log Bimbingan (Min 10x)</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Tahap 3: Sidang Skripsi</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-certificate"></i>
							<span class="nav-text">Sidang & Revisi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('thesis-defenses.index') }}">1. Pendaftaran & Eligibility Sidang</a></li>
                            <li><a href="{{ route('defense-examiners.index') }}">2. Tim Penguji Sidang</a></li>
                            <li><a href="{{ route('defense-revisions.index') }}">3. Upload & Approval Revisi (Penguji + Kaprodi)</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Tahap 4: Yudisium & Kelulusan</li>
					<li><a href="{{ route('yudisiums.index') }}" aria-expanded="false">
							<i class="la la-trophy"></i>
							<span class="nav-text">SK Yudisium & Cetak</span>
						</a>
                    </li>
                    @endif
				</ul>
            </div>
        </div>