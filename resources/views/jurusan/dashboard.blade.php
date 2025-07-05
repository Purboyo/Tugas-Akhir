@extends('jurusan.app')

@section('title', 'Dashboard Jurusan')

@section('content')
<div class="container-fluid py-4 text-dark">
    <h2 class="mb-4">Dashboard Jurusan</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h5 class="card-title">Laporan Reviewed</h5>
                    <h3 class="text-info">{{ $totalReviewed }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <h5 class="card-title">Laporan Resolved</h5>
                    <h3 class="text-success">{{ $totalResolved }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Laboratorium</h5>
                    <h3 class="text-primary">{{ $totalLab }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Distribusi Laporan</h5>
            <canvas id="reportChart" height="100"></canvas>
        </div>
    </div>

    {{-- Tabel laporan terbaru --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Laporan Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-bordered text-dark">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PC - Lab</th>
                            <th>Teknisi</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestReports as $i => $report)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $report->pc->pc_name }} - {{ $report->pc->lab->lab_name }}</td>
                            <td>{{ $report->technician->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $report->status === 'reviewed' ? 'bg-info text-dark' : 'bg-success text-white' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td>{{ $report->description }}</td>
                            <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('reportChart').getContext('2d');
    const reportChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Reviewed', 'Resolved'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $totalReviewed }}, {{ $totalResolved }}],
                backgroundColor: ['#17a2b8', '#28a745'],
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
</script>
@endsection
