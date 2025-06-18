@extends('teknisi.app')
@section('title', 'History Maintenance')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">History Maintenance</h2>

    <div class="col-xl-14 mb-0"> 
        <div class="card mb-0"> 
            <div class="card-body pb-2 pt-3">
                <div class="mb-2">
                    <h4 class="card-title">Pilih Maintenance</h4>
                </div>

                <form method="GET" action="{{ route('teknisi.maintenance.history') }}">
                    <select class="form-control js-select2" name="maintenance_id" id="maintenance_id">
                        <option value="">-- Semua Maintenance --</option>
                        @foreach ($maintenances as $m)
                            <option value="{{ $m->id }}" {{ $selectedId == $m->id ? 'selected' : '' }}>
                                {{ $m->laboratory->lab_name }} - {{ $m->created_at->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary mt-3">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>



    {{-- Chart --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Maintenance Status Chart</h4>
        </div>
        <div style="max-width: 500px; margin: auto;">
            <canvas id="statusChart" class="w-full max-w-md h-64 mx-auto"></canvas>
        </div>
    </div>


    {{-- Table --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat Maintenance</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PC</th>
                        <th>Status</th>
                        <th>Lab</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $row->pc->pc_name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $row->status == 'Good' ? 'success' : 'danger' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td>{{ $row->maintenance->laboratory->lab_name ?? '-' }}</td>
                        <td>{{ $row->maintenance->user->name ?? '-' }}</td>
                        <td>{{ $row->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">
    <nav>
        <ul class="pagination pagination-sm pagination-gutter">
            {{-- Previous Page --}}
            <li class="page-item {{ $history->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $history->previousPageUrl() }}">
                    <i class="icon-arrow-left"></i>
                </a>
            </li>

            {{-- Page Numbers --}}
            @for ($i = 1; $i <= $history->lastPage(); $i++)
            <li class="page-item {{ $i == $history->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $history->url($i) }}">{{ $i }}</a>
            </li>
            @endfor

            {{-- Next Page --}}
            <li class="page-item {{ !$history->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $history->nextPageUrl() }}">
                    <i class="icon-arrow-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode(array_keys($chartData)) !!};
    const dataValues = {!! json_encode(array_values($chartData)) !!};

    // Map warna berdasarkan label
    const backgroundColors = labels.map(label => {
        if (label === 'Good') return '#28a745'; // hijau
        if (label === 'Bad') return '#dc3545';  // merah
        return '#6c757d'; // abu-abu untuk selain itu
    });

    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>


<script>
    $(document).ready(function() {
        $('.js-select2').select2({
            width: '100%',
            placeholder: "Pilih Maintenance"
        });
    });
</script>

@endsection

