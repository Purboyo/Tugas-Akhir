@extends('teknisi.app')

@section('content')
<div class="container mt-4">
    <h3>Laporan ke Kepala Lab - PC Rusak</h3>
    <form action="{{ route('teknisi.report.submitToHead') }}" method="POST">
        @csrf
        @foreach ($badReports as $report)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>PC: {{ $report->pc->pc_name ?? 'PC-' . $report->pc_id }}</h5>
                    <p><strong>Lab:</strong> {{ $report->pc->lab->lab_name ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ $report->status }}</p>
                    <div class="form-group">
                        <label for="remarks[{{ $report->id }}]">Keterangan:</label>
                        <textarea name="remarks[{{ $report->id }}]" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-outline-primary">Kirim ke Kepala Lab</button>
    </form>
</div>
@endsection
