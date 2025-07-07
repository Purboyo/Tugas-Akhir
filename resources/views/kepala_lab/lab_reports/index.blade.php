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
            <div class="tab-pane fade text-dark {{ $index === 0 ? 'show active' : '' }}" id="lab-{{ $lab->id }}" role="tabpanel">
                <div class="pt-3">
                    @php
                        $labReports = $labReportsGrouped[$lab->id] ?? collect();
                        $pendingReports = $labReports->where('status', 'Pending');
                        $sendReports = $labReports->where('status', 'Send');
                    @endphp

                    {{-- Tabel laporan PENDING --}}
                    @if($pendingReports->isEmpty())
                        <p class="text-muted">No pending damage report found.</p>
                    @else
                    <div class="table-responsive">
                        <h5 class="mb-2">Pending Damage Report</h5>
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
                                @foreach($pendingReports as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $report->pc->pc_name ?? '-' }}</td>
                                    <td>{{ $report->technician->name ?? '-' }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ $report->status }}</span></td>
                                    <td>{{ $report->description }}</td>
                                    <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $report->handling_notes }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal-{{ $report->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
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
                                                            <option value="Send" {{ $report->status === 'Send' ? 'selected' : '' }}>Send</option>
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

                    {{-- Tabel laporan SEND --}}
                    @if($sendReports->isNotEmpty())
                    <div class="table-responsive mt-5">
                        <h5 class="mb-2 text-info">Sent Damage Report</h5>
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
                                @foreach($sendReports as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $report->pc->pc_name ?? '-' }}</td>
                                    <td>{{ $report->technician->name ?? '-' }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $report->status }}</span></td>
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
