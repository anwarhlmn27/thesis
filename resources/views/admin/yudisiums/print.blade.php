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
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .content-table td {
            padding: 6px 12px;
            font-size: 16px;
        }
        .ttd-box {
            margin-top: 50px;
            float: right;
            width: 300px;
            text-align: center;
        }
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

<div class="container bg-white p-4 border rounded shadow-sm">
    <!-- Kop Surat -->
    <div class="kop-surat text-center">
        <h3 class="fw-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3>
        <h4 class="fw-bold mb-1">FAKULTAS ILMU KOMPUTER & TEKNOLOGI INFORMASI</h4>
        <p class="mb-0">Jl. Universitas No. 1 Kompleks Kampus | Telp: (021) 1234567 | Email: info@kampus.ac.id</p>
    </div>

    <!-- Title SK -->
    <div class="title-sk">
        <h4 class="mb-1 text-decoration-underline fw-bold">SURAT KEPUTUSAN DEKAN</h4>
        <p class="mb-0 fs-5">Nomor: {{ $yudisium->sk_number }}</p>
        <p class="fs-6 text-muted">TENTANG PENETAPAN KELULUSAN MAHASISWA (YUDISIUM SKRIPSI)</p>
    </div>

    <div class="mb-4 fs-6">
        <p>Menimbang dan memperhatikan hasil ujian sidang skripsi serta verifikasi kelengkapan berkas akademik, keuangan, dan perpustakaan, Dekan Fakultas Ilmu Komputer menyatakan bahwa mahasiswa tersebut di bawah ini:</p>
    </div>

    <!-- Data Mahasiswa -->
    <table class="table table-borderless content-table mb-4">
        <tr>
            <td style="width: 220px;" class="fw-bold">Nama Mahasiswa</td>
            <td style="width: 20px;">:</td>
            <td><strong>{{ $yudisium->student->user->name ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="fw-bold">NIM</td>
            <td>:</td>
            <td>{{ $yudisium->student->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Program Studi</td>
            <td>:</td>
            <td>{{ $yudisium->student->prodi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Judul Skripsi</td>
            <td>:</td>
            <td><i>"{{ $yudisium->thesis->title ?? '-' }}"</i></td>
        </tr>
        <tr>
            <td class="fw-bold">Tanggal Kelulusan</td>
            <td>:</td>
            <td>{{ $yudisium->graduation_date ? $yudisium->graduation_date->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Status Kelulusan</td>
            <td>:</td>
            <td><span class="badge bg-success fs-6">LULUS / YUDISIUM RESMI</span></td>
        </tr>
    </table>

    <div class="mb-5 fs-6">
        <p>Demikian Surat Keputusan ini dibuat untuk dapat dipergunakan sebagaimana mestinya dan berlaku sejak tanggal ditetapkan.</p>
    </div>

    <!-- Signature Block Dekan -->
    <div class="row">
        <div class="col-7"></div>
        <div class="col-5">
            <div class="text-center">
                <p class="mb-1">Ditetapkan di : Jakarta</p>
                <p class="mb-4">Pada tanggal : {{ date('d F Y') }}</p>
                <p class="fw-bold mb-5">Dekan Fakultas,</p>
                <br><br>
                <p class="fw-bold text-decoration-underline mb-0 fs-5">{{ $yudisium->dekan_name ?? 'Dr. H. Ahmad Dahlan, M.Pd.' }}</p>
                <p class="mb-0">NIP. {{ $yudisium->dekan_nip ?? '197508152002121001' }}</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
