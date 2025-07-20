<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report History</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead {
            background-color: #f2f2f2;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@foreach ($data as $labName => $items)
    <h2>PC Report - {{ $labName }}</h2>

    <div class="info">
        <p><strong>Laboratory:</strong> {{ $labName }}</p>
        <p><strong>Filtered Dates:</strong> 
            {{ implode(', ', collect($dates)->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->toArray()) }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PC</th>
                <th>Status</th>
                <th>Technician</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->pc->pc_name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $row->status === 'Good' ? 'badge-success' : 'badge-danger' }}">
                            {{ $row->status }}
                        </span>
                    </td>
                    <td>{{ $row->technician->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Printed on: {{ now()->format('d M Y H:i') }}
    </div>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach



</body>
</html>
