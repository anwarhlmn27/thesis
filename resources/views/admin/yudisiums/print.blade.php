<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SURAT KEPUTUSAN YUDISIUM - {{ $yudisium->sk_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            padding: 40px;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title-sk {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content-table td {
            padding: 6px 12px;
            font-size: 16px;
            vertical-align: top;
        }
        .ttd-box {
            margin-top: 50px;
            float: right;
            width: 300px;
            text-align: center;
        }
        ol { padding-left: 20px; }
        .page-break { page-break-before: always; }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="container no-print mb-4">
    <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
        <i class="fa fa-print"></i> Cetak SK Yudisium (PDF / Printer)
    </button>
    <a href="{{ route('yudisiums.index') }}" class="btn btn-secondary btn-lg ms-2">
        Kembali ke Daftar
    </a>
</div>

<div class="container bg-white p-4">
    <!-- Kop Surat (Disesuaikan dengan PDF) -->
    <div class="kop-surat text-center">
        <h3 class="fw-bold mb-1" style="color: darkred;">UNIVERSITAS HORIZON INDONESIA</h3>
        <h5 class="fw-bold mb-1" style="letter-spacing: 2px;">K A R A W A N G</h5>
        <p class="mb-0" style="font-size: 12px;">
            Jln Pangkal Perjuangan Km. 1 By Pass Karawang, Kel Tanjungpura, Kec Karawang Barat, Kab Karawang, Jawa Barat 41316<br>
            Website : www.horizon.ac.id Email : info.krw@horizon.ac.id
        </p>
    </div>

    <!-- Title SK -->
    <div class="title-sk">
        <h5 class="mb-1 fw-bold">SURAT KEPUTUSAN</h5>
        <h5 class="mb-1 fw-bold">DEKAN FAKULTAS TEKNOLOGI INFORMASI DAN KOMPUTER</h5>
        <h5 class="mb-1 fw-bold">UNIVERSITAS HORIZON INDONESIA</h5>
        <h5 class="mb-3 fw-bold">NOMOR : {{ $yudisium->sk_number }}</h5>
        
        <h5 class="mb-1 fw-bold">TENTANG</h5>
        <h5 class="mb-1 fw-bold">YUDISIUM PROGRAM SARJANA</h5>
        <h5 class="mb-1 fw-bold">UNIVERSITAS HORIZON INDONESIA</h5>
        <h5 class="mb-4 fw-bold">TAHUN AKADEMIK {{ $yudisium->academic_year ?? '..........' }}</h5>
    </div>

    <table class="table table-borderless content-table mb-4">
        <tr>
            <td style="width: 150px;">Menimbang</td>
            <td style="width: 10px;">:</td>
            <td>
                <ol type="a" class="mb-0">
                    <li class="mb-2">Bahwa Ujian Sidang Skripsi Mahasiswa Fakultas Teknologi Informasi dan Komputer, Universitas Horizon Indonesia, Karawang telah selesai dilaksanakan;</li>
                    <li>Bahwa untuk memperlancar dan ketertiban Program Akademik Tahun Akademik {{ $yudisium->academic_year ?? '..........' }} dan untuk melepas secara resmi Mahasiswa yang telah dinyatakan lulus perlu dilakukan dalam suatu Upacara Yudisium;</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td>Mengingat</td>
            <td>:</td>
            <td>
                <ol class="mb-0">
                    <li class="mb-1">Undang-undang Nomor 20 Tahun 2003, tentang Sistem Pendidikan Nasional;</li>
                    <li class="mb-1">Undang-undang Republik Indonesia Nomor 12 Tahun 2012, tentang Pendidikan Tinggi;</li>
                    <li class="mb-1">Peraturan Pemerintah Nomor 4 Tahun 2014, Tentang Penyelenggaraan Pendidikan Tinggi dan pengelolaan Perguruan Tinggi;</li>
                    <li class="mb-1">Statuta Sekolah Tinggi Manajemen Informatika dan Komputer (STMIK) Horizon Karawang;</li>
                    <li class="mb-1">Surat Keputusan Mendikbud Nomor 595/E/O/2023 tentang izin penggabungan menjadi Universitas Horizon Indonesia, Karawang.</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td>Memperhatikan</td>
            <td>:</td>
            <td>Hasil rapat yudisium akhir Program Studi Informatika Strata 1, Program Studi Sistem Informasi Strata 1 tanggal {{ $yudisium->graduation_date ? $yudisium->graduation_date->copy()->subDays(1)->format('d F Y') : '..........' }}.</td>
        </tr>
    </table>

    <div class="text-center fw-bold fs-5 mb-4">MEMUTUSKAN :</div>

    <table class="table table-borderless content-table mb-4">
        <tr>
            <td style="width: 150px;">Menetapkan</td>
            <td style="width: 10px;">:</td>
            <td>Surat Keputusan Dekan Fakultas Teknologi Informasi dan Komputer, Universitas Horizon Indonesia Tentang Yudisium Program Sarjana ke 2 Tahun Akademik {{ $yudisium->academic_year ?? '..........' }}.</td>
        </tr>
        <tr>
            <td>Pertama</td>
            <td>:</td>
            <td>Melepas secara resmi pada tanggal {{ $yudisium->graduation_date ? $yudisium->graduation_date->format('d F Y') : '..........' }} Yudisium Program Sarjana seperti tersebut dalam lampiran Surat Keputusan ini;</td>
        </tr>
        <tr>
            <td>Kedua</td>
            <td>:</td>
            <td>Surat Keputusan ini mulai berlaku sejak tanggal ditetapkan dan apabila di kemudian hari ternyata terdapat kekeliruan dalam Surat Keputusan ini, maka akan dilakukan perbaikan atau perubahan sebagaimana mestinya.</td>
        </tr>
    </table>

    <!-- Signature Block Dekan -->
    <div class="row">
        <div class="col-7"></div>
        <div class="col-5">
            <div>
                <p class="mb-1">Ditetapkan di Karawang</p>
                <p class="mb-4">Pada tanggal {{ $yudisium->graduation_date ? $yudisium->graduation_date->format('d F Y') : '..........' }}</p>
                <p class="fw-bold mb-5">Dekan FTIK,</p>
                <br><br>
                <p class="fw-bold mb-0 fs-6">{{ $yudisium->dekan_name ?? 'Dr. H. Ahmad Dahlan, M.Pd.' }}</p>
                <p class="mb-0 fw-bold">NIK : {{ $yudisium->dekan_nip ?? '197508152002121001' }}</p>
            </div>
        </div>
    </div>

    <!-- LAMPIRAN -->
    <div class="page-break"></div>
    
    <div class="kop-surat text-center mb-4">
        <h3 class="fw-bold mb-1" style="color: darkred;">UNIVERSITAS HORIZON INDONESIA</h3>
        <h5 class="fw-bold mb-1" style="letter-spacing: 2px;">K A R A W A N G</h5>
    </div>

    <table class="table table-borderless content-table mb-4" style="line-height: 1;">
        <tr>
            <td colspan="3" class="p-1">Lampiran Surat Keputusan Dekan Fakultas Teknologi Informasi dan Komputer Universitas Horizon Indonesia</td>
        </tr>
        <tr>
            <td style="width: 120px;" class="p-1">Nomor</td>
            <td style="width: 10px;" class="p-1">:</td>
            <td class="p-1">{{ $yudisium->sk_number }}</td>
        </tr>
        <tr>
            <td class="p-1">Tanggal</td>
            <td class="p-1">:</td>
            <td class="p-1">{{ $yudisium->graduation_date ? $yudisium->graduation_date->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="p-1">Tentang</td>
            <td class="p-1">:</td>
            <td class="p-1 text-justify">Yudisium Program Sarjana Fakultas Teknologi Informasi Dan Komputer Tahun Akademik {{ $yudisium->academic_year ?? '..........' }} Pada Universitas Horizon Indonesia.</td>
        </tr>
    </table>

    @php
        $groupedStudents = $yudisium->students->groupBy('prodi');
    @endphp

    @forelse($groupedStudents as $prodi => $students)
        <h5 class="fw-bold mt-4 mb-2">Prodi {{ $prodi }}</h5>
        <table class="table table-bordered border-dark">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center">Nim</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Ipk</th>
                    <th class="text-center">Predikat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $student->nim }}</td>
                    <td>{{ $student->user->name ?? '' }}</td>
                    <td class="text-center">{{ number_format($student->pivot->ipk ?? 0, 2, ',', '.') }}</td>
                    <td>{{ $student->pivot->predicate ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <div class="alert alert-warning mt-4 text-center">
            Belum ada mahasiswa yang dilampirkan pada SK Yudisium ini.
        </div>
    @endforelse

</div>

</body>
</html>
