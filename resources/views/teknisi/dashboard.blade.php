@extends('teknisi.app')

@section('content')
<div class="container-fluid mt-4 text-dark">
    <h2 class="font-weight-bold mb-3">Technician Dashboard</h2>
    <p class="text-muted">Summary of your labs, today's reports, and upcoming maintenance.</p>
    {{-- Maintenance Reminders --}}
    <div class="row">
        <div class="col-12 mb-3">
            <h5 class="font-weight-bold">Upcoming Maintenance</h5>
        </div>

        @foreach ($reminders as $reminder)
        <div class="col-md-4 mb-3">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $reminder->title }}</h5>
                    <p class="mb-1"><strong>Lab:</strong> {{ $reminder->laboratory->lab_name }}</p>
                    <p class="mb-1"><strong>Date:</strong> {{ $reminder->reminder_date->format('d M Y') }}</p>
                    <p class="mb-0">
                        <strong>Status:</strong>
                        @php
                            $status = $reminder->computed_status;
                        @endphp
                        @if ($status === 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif ($status === 'missed')
                            <span class="badge badge-danger">Missed</span>
                        @else
                            <span class="badge badge-warning">Upcoming</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{-- Chart --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary ">
                    <h6 class="mb-0 text-white">Report Summary (Today)</h6>
                </div>
                <div class="card-body">
                    <canvas id="reportChart" height="70"></canvas>
                </div>
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
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Total PCs',
                    data: {!! json_encode($chartPCs) !!},
                    backgroundColor: 'rgba(0, 123, 255, 0.4)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Good Reports',
                    data: {!! json_encode($chartGood) !!},
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Bad Reports',
                    data: {!! json_encode($chartBad) !!},
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
</script>
@endsection