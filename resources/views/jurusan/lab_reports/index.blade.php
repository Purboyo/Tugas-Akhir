@extends('jurusan.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Laboratory Reviewed Damage Reports</h4>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($labs as $index => $lab)
            <li class="nav-item text-dark">
                <a class="nav-link {{ $index === 0 ? 'active' : '' }}" data-toggle="tab" href="#lab-{{ $lab->id }}">
                    {{ $lab->lab_name }}
                </a>
            </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($labs as $index => $lab)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="lab-{{ $lab->id }}" role="tabpanel">
                <div class="pt-3">
                    @php
                        $labReports = $labReportsGrouped[$lab->id] ?? collect();
                    @endphp

                    @if($labReports->isEmpty())
                        <p class="text-muted">No reviewed damage reports found in this lab.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered text-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PC</th>
                                    <th>Technician</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($labReports as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $report->pc->pc_name ?? '-' }}</td>
                                    <td>{{ $report->technician->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $report->status }}</span>
                                    </td>
                                    <td>{{ $report->description }}</td>
                                    <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $report->handling_notes }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
