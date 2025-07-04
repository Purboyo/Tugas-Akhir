@extends('kepala_lab.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4 text-dark">
    {{-- Di atas card --}}
    <h2 class="mb-4">Welcome, Head of Laboratory</h2>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-primary h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Laboratories</h6>
                    <h3 class="text-primary">{{ $labs->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-success h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Reports</h6>
                    <h3 class="text-success">{{ $totalReports }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-warning h-100">
                <div class="card-body">
                    <h6 class="text-muted">Pending Reports</h6>
                    <h3 class="text-warning">{{ $pendingReports }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-info h-100">
                <div class="card-body">
                    <h6 class="text-muted">Resolved Reports</h6>
                    <h3 class="text-info">{{ $resolvedReports }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Overview --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Report Status Overview</h6>
        </div>
        <div class="card-body">
            <canvas id="overviewChart" height="100"></canvas>
        </div>
    </div>

    {{-- Optional: Table or Cards per Lab --}}
    {{-- 
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Reports per Laboratory</h6>
        </div>
        <div class="card-body">
            @foreach ($labReportsGrouped as $labId => $reports)
                <h5 class="mt-3">Lab: {{ $reports->first()->pc->lab->name ?? 'Unknown' }}</h5>
                <p>Total Reports: {{ $reports->count() }}</p>
            @endforeach
        </div>
    </div>
    --}}

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('overviewChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($chartData)) !!},
            datasets: [{
                label: 'Count',
                data: {!! json_encode(array_values($chartData)) !!},
                backgroundColor: [
                    'rgba(255, 193, 7, 0.6)', // Pending
                    'rgba(40, 167, 69, 0.6)', // Resolved
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(40, 167, 69, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
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
@endpush
