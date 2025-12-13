@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Personal Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                    <h4 class="mb-0">{{ $personalStats['hadir'] }}</h4>
                    <small class="text-muted">Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-times fa-2x text-warning mb-2"></i>
                    <h4 class="mb-0">{{ $personalStats['izin'] }}</h4>
                    <small class="text-muted">Izin</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-hospital fa-2x text-info mb-2"></i>
                    <h4 class="mb-0">{{ $personalStats['sakit'] }}</h4>
                    <small class="text-muted">Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h4 class="mb-0">{{ $personalStats['alpha'] }}</h4>
                    <small class="text-muted">Alpha</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Performance -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Persentase Kehadiran
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <canvas id="attendanceRateChart" width="200" height="200"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h2 class="mb-0 text-primary">{{ $personalStats['attendance_rate'] }}%</h2>
                            <small class="text-muted">Kehadiran</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Trend Kehadiran
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceTrendsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Performa per Mata Kuliah
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($coursePerformance) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Mata Kuliah</th>
                                        <th>Kehadiran</th>
                                        <th>Persentase</th>
                                        <th>Grade</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coursePerformance as $course)
                                    <tr>
                                        <td>{{ $course['course_name'] }}</td>
                                        <td>{{ $course['hadir'] }}/{{ $course['total'] }}</td>
                                        <td>
                                            <span class="badge
                                                @if($course['attendance_rate'] >= 90) bg-success
                                                @elseif($course['attendance_rate'] >= 75) bg-primary
                                                @elseif($course['attendance_rate'] >= 60) bg-warning
                                                @else bg-danger
                                                @endif">
                                                {{ $course['attendance_rate'] }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($course['grade'] == 'A') bg-success
                                                @elseif($course['grade'] == 'B') bg-primary
                                                @elseif($course['grade'] == 'C') bg-warning
                                                @elseif($course['grade'] == 'D') bg-info
                                                @else bg-danger
                                                @endif fs-6">
                                                {{ $course['grade'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar
                                                    @if($course['attendance_rate'] >= 90) bg-success
                                                    @elseif($course['attendance_rate'] >= 75) bg-primary
                                                    @elseif($course['attendance_rate'] >= 60) bg-warning
                                                    @else bg-danger
                                                    @endif"
                                                    style="width: {{ $course['attendance_rate'] }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada data presensi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Class Comparison -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-balance-scale me-2"></i>Perbandingan dengan Kelas
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($classComparison) > 0)
                        <div class="row">
                            @foreach($classComparison as $comparison)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">{{ $comparison['course_name'] }}</h6>

                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <div class="p-2 bg-primary bg-opacity-10 rounded">
                                                    <div class="h5 mb-0 text-primary">{{ $comparison['student_rate'] }}%</div>
                                                    <small class="text-muted">Anda</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 bg-secondary bg-opacity-10 rounded">
                                                    <div class="h5 mb-0 text-secondary">{{ $comparison['class_rate'] }}%</div>
                                                    <small class="text-muted">Kelas</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            @if($comparison['difference'] > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-arrow-up me-1"></i>+{{ $comparison['difference'] }}% dari rata-rata kelas
                                                </span>
                                            @elseif($comparison['difference'] < 0)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-arrow-down me-1"></i>{{ $comparison['difference'] }}% dari rata-rata kelas
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="fas fa-equals me-1"></i>Sama dengan rata-rata kelas
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada data untuk perbandingan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Attendance Rate Chart
const attendanceRateCtx = document.getElementById('attendanceRateChart').getContext('2d');
new Chart(attendanceRateCtx, {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Tidak Hadir'],
        datasets: [{
            data: [@json($personalStats['attendance_rate']), 100 - @json($personalStats['attendance_rate'])],
            backgroundColor: ['#28a745', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});

// Attendance Trends Chart
const attendanceTrendsCtx = document.getElementById('attendanceTrendsChart').getContext('2d');
new Chart(attendanceTrendsCtx, {
    type: 'line',
    data: {
        labels: @json(collect($attendanceTrends)->pluck('month')),
        datasets: [{
            label: 'Jumlah Kehadiran',
            data: @json(collect($attendanceTrends)->pluck('hadir')),
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endsection