@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                    <h4 class="mb-0">{{ $totalMatkuls }}</h4>
                    <small class="text-muted">Total Mata Kuliah</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h4 class="mb-0">{{ $totalEnrollments }}</h4>
                    <small class="text-muted">Total Mahasiswa</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x text-info mb-2"></i>
                    <h4 class="mb-0">{{ $totalPresences }}</h4>
                    <small class="text-muted">Total Presensi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x text-warning mb-2"></i>
                    <h4 class="mb-0">{{ $attendanceStats['attendance_rate'] }}%</h4>
                    <small class="text-muted">Rata-rata Kehadiran</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Trends Chart -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Trend Kehadiran 6 Bulan Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Attendance Distribution -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Distribusi Kehadiran
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceDistributionChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Analysis -->
    <div class="row mb-4">
        <!-- Top Performing Courses -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Mata Kuliah Terbaik
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($topCourses) > 0)
                        @foreach($topCourses as $course)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $course['name'] }}</h6>
                                <small class="text-muted">{{ $course['total_students'] }} mahasiswa</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success">{{ $course['attendance_rate'] }}%</span>
                                <br>
                                <small class="text-muted">{{ $course['total_presences'] }} presensi</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Belum ada data presensi</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Student Performance Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users-cog me-2"></i>Performa Mahasiswa
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceDistributionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($recentActivity) > 0)
                        <div class="timeline">
                            @foreach($recentActivity as $activity)
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <p class="mb-1">{{ $activity['description'] }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $activity['date']->diffForHumans() }}
                                        @if($activity['status'])
                                            <span class="badge
                                                @if($activity['status'] == 'hadir') bg-success
                                                @elseif($activity['status'] == 'izin') bg-warning
                                                @elseif($activity['status'] == 'sakit') bg-info
                                                @else bg-danger
                                                @endif ms-2">
                                                {{ ucfirst($activity['status']) }}
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada aktivitas terbaru</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trends Chart
const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
new Chart(monthlyTrendsCtx, {
    type: 'line',
    data: {
        labels: @json(collect($monthlyTrends)->pluck('month')),
        datasets: [{
            label: 'Persentase Kehadiran',
            data: @json(collect($monthlyTrends)->pluck('rate')),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
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

// Attendance Distribution Chart
const attendanceDistributionCtx = document.getElementById('attendanceDistributionChart').getContext('2d');
new Chart(attendanceDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Tidak Hadir'],
        datasets: [{
            data: [@json($attendanceStats['attendance_rate']), @json($attendanceStats['absent_rate'])],
            backgroundColor: ['#28a745', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Performance Distribution Chart
const performanceDistributionCtx = document.getElementById('performanceDistributionChart').getContext('2d');
new Chart(performanceDistributionCtx, {
    type: 'bar',
    data: {
        labels: ['Sangat Baik (≥90%)', 'Baik (75-89%)', 'Cukup (60-74%)', 'Perlu Perhatian (<60%)'],
        datasets: [{
            label: 'Jumlah Mahasiswa',
            data: [
                @json($performanceDistribution['excellent']),
                @json($performanceDistribution['good']),
                @json($performanceDistribution['average']),
                @json($performanceDistribution['poor'])
            ],
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
            borderWidth: 0
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

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-left: 30px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -45px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    z-index: 1;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -39px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}
</style>
@endsection