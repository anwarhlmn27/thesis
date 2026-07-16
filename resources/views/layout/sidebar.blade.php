<div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a href="{{ route('dashboard') }}" aria-expanded="false">
							<i class="la la-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                    </li>
					
                    <li class="nav-label">Data Master</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-users"></i>
							<span class="nav-text">Data Pengguna</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('users.index') }}">Data Admin</a></li>
                            <li><a href="{{ route('lecturers.index') }}">Data Dosen</a></li>
                            <li><a href="{{ route('students.index') }}">Data Mahasiswa</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Manajemen Skripsi</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-graduation-cap"></i>
							<span class="nav-text">Data Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('theses.index') }}">Daftar Skripsi</a></li>
                            <li><a href="{{ route('thesis-advisors.index') }}">Pembimbing Skripsi</a></li>
                        </ul>
                    </li>
					<li><a href="{{ route('mentoring-logs.index') }}" aria-expanded="false">
							<i class="la la-book"></i>
							<span class="nav-text">Log Bimbingan</span>
						</a>
                    </li>

                    <li class="nav-label">Ujian & Sidang</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-file-text"></i>
							<span class="nav-text">Seminar Proposal</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('proposal-seminars.index') }}">Jadwal Seminar</a></li>
                            <li><a href="{{ route('proposal-examiners.index') }}">Penguji Seminar</a></li>
                            <li><a href="{{ route('proposal-comments.index') }}">Revisi & Komentar</a></li>
                        </ul>
                    </li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-certificate"></i>
							<span class="nav-text">Sidang Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('thesis-defenses.index') }}">Jadwal Sidang</a></li>
                            <li><a href="{{ route('defense-examiners.index') }}">Penguji Sidang</a></li>
                            <li><a href="{{ route('defense-revisions.index') }}">Revisi Sidang</a></li>
                        </ul>
                    </li>
					<li><a href="{{ route('yudisiums.index') }}" aria-expanded="false">
							<i class="la la-trophy"></i>
							<span class="nav-text">Yudisium</span>
						</a>
                    </li>
				</ul>
            </div>
        </div>