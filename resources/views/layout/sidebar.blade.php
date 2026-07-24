<div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a href="{{ route('dashboard') }}" aria-expanded="false">
							<i class="la la-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>
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
				</ul>
            </div>
        </div>