<?php
$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);
$menu = <<<EOT
<ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a href="/" aria-expanded="false">
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
                            <li><a href="javascript:void()">Data Admin</a></li>
                            <li><a href="javascript:void()">Data Dosen</a></li>
                            <li><a href="javascript:void()">Data Mahasiswa</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Manajemen Skripsi</li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-graduation-cap"></i>
							<span class="nav-text">Data Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="javascript:void()">Daftar Skripsi</a></li>
                            <li><a href="javascript:void()">Pembimbing Skripsi</a></li>
                        </ul>
                    </li>
					<li><a href="javascript:void()" aria-expanded="false">
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
                            <li><a href="javascript:void()">Jadwal Seminar</a></li>
                            <li><a href="javascript:void()">Penguji Seminar</a></li>
                            <li><a href="javascript:void()">Revisi & Komentar</a></li>
                        </ul>
                    </li>
					<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
							<i class="la la-certificate"></i>
							<span class="nav-text">Sidang Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="javascript:void()">Jadwal Sidang</a></li>
                            <li><a href="javascript:void()">Penguji Sidang</a></li>
                            <li><a href="javascript:void()">Revisi Sidang</a></li>
                        </ul>
                    </li>
					<li><a href="javascript:void()" aria-expanded="false">
							<i class="la la-trophy"></i>
							<span class="nav-text">Yudisium</span>
						</a>
                    </li>
				</ul>
EOT;

$pattern = '/<ul class="metismenu" id="menu">.*?<\/ul>/s';
$content = preg_replace($pattern, $menu, $content);
file_put_contents($file, $content);
echo "Done!\n";
