@extends('kepala_lab.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Laboratory Damage Report</h4>
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
                        $activeReports = $labReports->where('status', '!=', 'Resolved');
                        $resolvedReports = $labReports->where('status', 'Resolved');
                    @endphp

                    {{-- Tabel laporan aktif --}}
                    @if($activeReports->isEmpty())
                        <p class="text-muted">No active damage report found.</p>
                    @else
                    <div class="table-responsive">
                        <h5 class="mb-2">Active Damage Report</h5>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeReports as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $report->pc->pc_name ?? '-' }}</td>
                                    <td>{{ $report->technician->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($report->status) {
                                                'Pending' => 'bg-warning text-dark',
                                                'Reviewed' => 'bg-info text-dark',
                                                default => 'bg-secondary text-white',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $report->status }}</span>
                                    </td>
                                    <td>{{ $report->description }}</td>
                                    <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $report->handling_notes }}</td>
                                    <td>
                                        <!-- Tombol Edit Modal -->
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal-{{ $report->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal-{{ $report->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <form method="POST" action="{{ route('kepala_lab.labreport.update', $report->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Edit Report</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-dark">
                                                    <div class="mb-3">
                                                        <label>Status</label>
                                                        <select class="form-control" name="status" required>
                                                            <option value="Pending" {{ $report->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="Reviewed" {{ $report->status === 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                                                            <option value="Resolved" {{ $report->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Handling Notes</label>
                                                        <textarea name="handling_notes" class="form-control" rows="3">{{ $report->handling_notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- Tabel laporan yang sudah diselesaikan --}}
                    @if($resolvedReports->isNotEmpty())
                    <div class="table-responsive mt-5">
                        <h5 class="mb-2 text-success">Resolved Damage Report</h5>
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
                                @foreach($resolvedReports as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $report->pc->pc_name ?? '-' }}</td>
                                    <td>{{ $report->technician->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-success text-white">{{ $report->status }}</span>
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
