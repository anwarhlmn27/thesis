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
					<li><a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
							<i class="la la-users"></i>
							<span class="nav-text">Data Pengguna</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Data Admin</a></li>
                            <li><a href="#">Data Dosen</a></li>
                            <li><a href="#">Data Mahasiswa</a></li>
                        </ul>
                    </li>

                    <li class="nav-label">Manajemen Skripsi</li>
					<li><a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
							<i class="la la-graduation-cap"></i>
							<span class="nav-text">Data Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Daftar Skripsi</a></li>
                            <li><a href="#">Pembimbing Skripsi</a></li>
                        </ul>
                    </li>
					<li><a href="#" aria-expanded="false">
							<i class="la la-book"></i>
							<span class="nav-text">Log Bimbingan</span>
						</a>
                    </li>

                    <li class="nav-label">Ujian & Sidang</li>
					<li><a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
							<i class="la la-file-text"></i>
							<span class="nav-text">Seminar Proposal</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Jadwal Seminar</a></li>
                            <li><a href="#">Penguji Seminar</a></li>
                            <li><a href="#">Revisi & Komentar</a></li>
                        </ul>
                    </li>
					<li><a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
							<i class="la la-certificate"></i>
							<span class="nav-text">Sidang Skripsi</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Jadwal Sidang</a></li>
                            <li><a href="#">Penguji Sidang</a></li>
                            <li><a href="#">Revisi Sidang</a></li>
                        </ul>
                    </li>
					<li><a href="#" aria-expanded="false">
							<i class="la la-trophy"></i>
							<span class="nav-text">Yudisium</span>
						</a>
                    </li>
				</ul>
EOT;

$pattern = '/<ul class="metismenu" id="menu">.*?<\/ul>\s*<div class="copyright">/s';
$replacement = $menu . "\n\n\n\t\t\t\t<div class=\"copyright\">";
$content = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $content);
echo "Done!\n";
