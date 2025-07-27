@extends('jurusan.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Laboratory Damage Report</h4>
    </div>
    <div class="card-body">
        {{-- Table untuk semua labReports --}}
        @if($labReports->isEmpty())
            <p class="text-muted">No damage reports found.</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered text-dark">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lab</th>
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
                    @foreach($labReports as $i => $report)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $report->pc->lab->lab_name ?? '-' }}</td>
                        <td>{{ $report->pc->pc_name ?? '-' }}</td>
                        <td>{{ $report->technician->name ?? '-' }}</td>
                        <td>
                        @php
                            $badgeClass = match($report->status) {
                                'Broken' => 'bg-danger text-white',
                                'Not Resolved' => 'bg-warning text-dark',
                                'Solved' => 'bg-success text-white',
                                'Suggested' => 'bg-primary text-white',
                                'Escalated' => 'bg-dark text-white',
                                'Reviewed' => 'bg-info text-dark',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                            <span class="badge {{ $badgeClass }}">{{ $report->status }}</span>
                        </td>
                        <td>{{ $report->description }}</td>
                        <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $report->handling_notes }}</td>
                        <td>
                            @if($report->status === 'Escalated')
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal-{{ $report->id }}">
                                    Edit
                                </button>
                            @endif
                        </td>

                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editModal-{{ $report->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <form method="POST" action="{{ route('jurusan.labreport.updatejurusan', $report->id) }}">
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
                                                <option value="Reviewed" {{ $report->status === 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Notes</label>
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
    </div>
</div>
@endsection
