@extends('kepala_lab.app')

@section('content')
<h2>Daftar Laporan dari Teknisi</h2>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>PC</th>
            <th>Teknisi</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($labReports as $index => $report)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $report->pc->pc_name ?? '-' }}</td>
            <td>{{ $report->technician->name ?? '-' }}</td>
            <td>{{ $report->description }}</td>
            <td>{{ $report->created_at->format('d M Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
