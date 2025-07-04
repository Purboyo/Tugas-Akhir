@extends(auth()->user()->role . '.app')

@section('title', 'Maintenance History')

@section('content')
<div class="container-fluid py-4 text-dark">
    <h2 class="mb-4">Maintenance History</h2>

    {{-- Filter Lab --}}
    <div class="card mb-4 shadow-lg">
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route($role . '.maintenance.history') }}">
                <div class="row">
                    <div class="col-md-12">
                        <label for="lab">Choose Laboratory:</label>
                        <select class="form-control" id="lab" name="lab">
                            <option value="">-- All Labs --</option>
                            @foreach ($availableLabs as $lab)
                                <option value="{{ $lab }}" {{ request('lab') == $lab ? 'selected' : '' }}>{{ $lab }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Global Chart --}}
    <div class="card mb-4 shadow-lg">
        <div class="card-header bg-light">
            <h5 class="mb-0">Overall Statistics</h5>
        </div>
        <div class="card-body text-center">
            <div style="max-width: 400px; margin: auto;">
                <canvas id="globalChart" style="width: 100%; max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Per Lab --}}
    @forelse ($groupedByLab as $labName => $data)
    @php
        $labId = Str::slug($labName);
        $total = $data->total();
        $good = $data->filter(fn($d) => $d->status === 'Good')->count();
        $bad = $data->filter(fn($d) => $d->status === 'Bad')->count();
    @endphp

    <div class="card mb-5 shadow-lg">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $labName }}</h5>

            {{-- Filter Tanggal --}}
            <form method="GET" class="d-flex align-items-center">
                <input type="hidden" name="lab" value="{{ request('lab') }}">
                <label for="date-{{ $labId }}" class="me-2 mb-0">Date:</label>
                <select name="date_{{ $labId }}" id="date-{{ $labId }}" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Dates --</option>
                    @foreach ($availableDatesPerLab[$labName] ?? [] as $date)
                        <option value="{{ $date }}" {{ request("date_$labId") == $date ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-body row">
            {{-- Chart --}}
            <div class="col-md-4 text-center border-end">
                <canvas id="chart-lab-{{ $labId }}" style="width: 100%; max-width: 400px; max-height: 200px;"></canvas>
                <div class="mt-3">
                    <small>
                        Total: <strong>{{ $total }}</strong><br>
                        Good: <strong>{{ $good }}</strong> ({{ round(($good / max($total,1)) * 100, 1) }}%)<br>
                        Bad: <strong>{{ $bad }}</strong> ({{ round(($bad / max($total,1)) * 100, 1) }}%)<br>
                    </small>
                </div>
            </div>

            {{-- Tabel & Note --}}
            <div class="col-md-8">
                <table class="table table-sm table-bordered table-hover text-dark">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>PC</th>
                            <th>Status</th>
                            <th>Technician</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->pc->pc_name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $row->status === 'Good' ? 'success' : 'danger' }}">
                                        {{ $row->status }}
                                    </span>
                                </td>
                                <td>{{ $row->maintenance->reminder->user->name ?? '-' }}</td>
                                <td>{{ $row->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="card-body">
                    <nav>
                        <ul class="pagination pagination-sm pagination-gutter justify-content-center">
                            <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $data->previousPageUrl() ?? '#' }}"><i class="icon-arrow-left"></i></a>
                            </li>
                            @for ($i = 1; $i <= $data->lastPage(); $i++)
                                @php
                                    $query = request()->except("page_lab_$labId");
                                    $query["page_lab_$labId"] = $i;
                                @endphp
                                <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ url()->current() . '?' . http_build_query($query) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ !$data->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $data->nextPageUrl() ?? '#' }}"><i class="icon-arrow-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>

                {{-- Note --}}
                @php
                    $noteText = $data->first()?->maintenance?->note;
                @endphp
                @if ($noteText)
                    <div class="card mt-4 bg-light">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Maintenance notes:</h6>
                            <p class="mb-0 text-dark">{{ $noteText }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="alert alert-info">No data found.</div>
    @endforelse
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const globalCtx = document.getElementById('globalChart').getContext('2d');

    new Chart(globalCtx, {
        type: 'doughnut',
        data: {
            labels: ['Good', 'Bad'],
            datasets: [{
                data: [{{ $chartData['Good'] ?? 0 }}, {{ $chartData['Bad'] ?? 0 }}],
                backgroundColor: ['#51cf66', '#ff6b6b'],
                hoverOffset: 20,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            cutout: '65%',
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        color: '#343a40',
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#dee2e6',
                    borderWidth: 1
                }
            }
        }
    });

    @foreach ($groupedByLab as $labName => $data)
        @php
            $labId = Str::slug($labName);
            $good = $data->where('status', 'Good')->count();
            $bad = $data->where('status', 'Bad')->count();
        @endphp

        new Chart(document.getElementById('chart-lab-{{ $labId }}').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Good', 'Bad'],
                datasets: [{
                    data: [{{ $good }}, {{ $bad }}],
                    backgroundColor: ['#63e6be', '#ffa8a8'],
                    hoverOffset: 20,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                cutout: '60%',
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            color: '#343a40',
                            padding: 10
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#000',
                        bodyColor: '#000',
                        borderColor: '#dee2e6',
                        borderWidth: 1
                    }
                }
            }
        });
    @endforeach

    // Lab dropdown filter auto-submit
    document.getElementById('lab').addEventListener('change', function () {
        this.form.submit();
    });
</script>
@endpush
