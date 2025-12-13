<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi - {{ $matkul->nama }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 5px 0;
        }
        .info-section .label {
            font-weight: bold;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #007bff;
        }
        .status-hadir { color: #28a745; font-weight: bold; }
        .status-izin { color: #ffc107; font-weight: bold; }
        .status-sakit { color: #17a2b8; font-weight: bold; }
        .status-alpha { color: #dc3545; font-weight: bold; }
        .summary {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .summary h3 {
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .summary-table {
            width: 100%;
            margin-top: 15px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .summary-table th {
            background-color: #e9ecef;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .percentage {
            font-size: 14px;
            font-weight: bold;
        }
        .percentage.high { color: #28a745; }
        .percentage.medium { color: #ffc107; }
        .percentage.low { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PRESENSI MAHASISWA</h1>
        <p>Mata Kuliah: {{ $matkul->nama }}</p>
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td class="label">Mata Kuliah:</td>
                <td>{{ $matkul->nama }}</td>
            </tr>
            <tr>
                <td class="label">Deskripsi:</td>
                <td>{{ $matkul->deskripsi }}</td>
            </tr>
            <tr>
                <td class="label">Dosen Pengampu:</td>
                <td>{{ $matkul->dosen->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Hari/Jam:</td>
                <td>{{ $matkul->hari }}, {{ $matkul->jam }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Generate:</td>
                <td>{{ $generated_at->format('d/m/Y H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NIM</th>
                <th style="width: 25%;">Nama Mahasiswa</th>
                <th style="width: 10%;">Hadir</th>
                <th style="width: 10%;">Izin</th>
                <th style="width: 10%;">Sakit</th>
                <th style="width: 10%;">Alpha</th>
                <th style="width: 15%;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data['mahasiswa']->nim }}</td>
                <td>{{ $data['mahasiswa']->user->name }}</td>
                <td class="status-hadir">{{ $data['stats']['hadir'] }}</td>
                <td class="status-izin">{{ $data['stats']['izin'] }}</td>
                <td class="status-sakit">{{ $data['stats']['sakit'] }}</td>
                <td class="status-alpha">{{ $data['stats']['alpha'] }}</td>
                <td>
                    <span class="percentage
                        @if($data['percentage'] >= 80) high
                        @elseif($data['percentage'] >= 60) medium
                        @else low
                        @endif">
                        {{ $data['percentage'] }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Kehadiran</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Mahasiswa</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalStudents = count($attendanceData);
                $categories = [
                    'Sangat Baik (≥80%)' => 0,
                    'Baik (60-79%)' => 0,
                    'Perlu Perhatian (<60%)' => 0
                ];

                foreach ($attendanceData as $data) {
                    if ($data['percentage'] >= 80) {
                        $categories['Sangat Baik (≥80%)']++;
                    } elseif ($data['percentage'] >= 60) {
                        $categories['Baik (60-79%)']++;
                    } else {
                        $categories['Perlu Perhatian (<60%)']++;
                    }
                }
                ?>

                @foreach($categories as $category => $count)
                <tr>
                    <td>{{ $category }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ $totalStudents > 0 ? round(($count / $totalStudents) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Presensi Universitas</p>
        <p>&copy; {{ date('Y') }} Universitas - Sistem Presensi Digital</p>
    </div>
</body>
</html>