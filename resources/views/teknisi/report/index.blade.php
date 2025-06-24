@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Report Management</h1>
            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}. Report Management</small>
        </div>
    </div>
</section>

<section class="section main-section">
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "timeOut": "3000"
                };
                toastr.success("{{ session('success') }}");
            });
        </script>
    @endif

    <div class="card has-table">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon h2"><i class="mdi mdi-file-document-box"> Report List</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route($role . '.report.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Cari nama/NPM/PC..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-magnify"></i></button>
                </form> 
            </div>
        </header>

        <div class="card-content px-4 py-2">
            <table class="table is-fullwidth is-striped text-dark">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>ID</th>
                        <th>PC</th>
                        <th>Form</th>
                        <th>Date</th>
                        <th>Status</th>
                        @if(auth()->user()->role === 'teknisi')
                        <th>Checklist</th>
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->reporter->name }}</td>
                            <td>{{ $report->reporter->npm }}</td>
                            <td>{{ $report->pc->pc_name ?? 'PC-' . $report->pc_id }}<br>
                                {{ $report->pc->lab->lab_name ?? 'Lab-' . optional($report->pc)->lab_id }}
                            </td>
                            <td>{{ $report->form->title }}</td>
                            <td>{{ $report->created_at->format('d M Y H:i') }}</td>                            
<td>
    {{ $report->status }} 
    @if(auth()->user()->role === 'teknisi')
    <a href="javascript:void(0)" title="Ubah Status"
        data-toggle="modal" data-target="#statusModal-{{ $report->id }}">   
        <i class="fa fa-pencil"></i>
    </a>
    @endif
</td>
<td>
    @if(auth()->user()->role === 'teknisi')
        <input type="checkbox" class="report-checkbox" data-id="{{ $report->id }}"
            {{ $report->checked ? 'checked' : '' }}>
    @endif
</td>
 
@if(auth()->user()->role === 'teknisi')
                            <td>
                                <a href="{{ route('teknisi.report.show', $report->id) }}" class="mr-4" data-toggle="tooltip"
                                   data-placement="top" title="Show">
                                    <i class="fa fa-eye"></i> Show
                                </a>
    <a href="javascript:void(0)" title="Delete"
       data-toggle="modal" data-target="#deleteModal-{{ $report->id }}">
        <i class="fa fa-close"></i> Delete
    </a>
                            
                                <!-- Modal -->
                                <div class="modal fade" id="deleteModal-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $report->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel-{{ $report->id }}">Confirm Delete</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the report <strong>{{ $report->form->title }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route($role . '.report.destroy', $report->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Modal Ubah Status -->
@if(auth()->user()->role === 'teknisi')
<!-- Modal Ubah Status -->
<div class="modal fade" id="statusModal-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel-{{ $report->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('teknisi.report.updateStatus', $report->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Ubah Status</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <label for="status-{{ $report->id }}">Status:</label>
          <select name="status" class="form-control" id="status-{{ $report->id }}" required>
            <option value="Good" {{ $report->status == 'Good' ? 'selected' : '' }}>Good</option>
            <option value="Bad" {{ $report->status == 'Bad' ? 'selected' : '' }}>Bad</option>
            <option value="Repairing" {{ $report->status == 'Repairing' ? 'selected' : '' }}>Repairing</option>
            <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>Pending</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-outline-info">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
@if(auth()->user()->role === 'teknisi')
<div class="mt-3 text-end">
    <button id="done-button" class="btn btn-outline-success" disabled>Done</button>
</div>
@endif
@if(auth()->user()->role === 'teknisi' && $reports->where('status', 'Bad')->count() > 0)
<div class="mt-3 text-end">
    <a href="{{ route('teknisi.report.reportBadForm') }}" class="btn btn-outline-warning">
        <i class="fa fa-paper-plane"></i> Laporkan ke Kepala Lab
    </a>
</div>
@endif


        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Checklist individual
        document.querySelectorAll('.report-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const reportId = this.getAttribute('data-id');
                const checked = this.checked;

                fetch(`/teknisi/report/${reportId}/check`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ checked: checked })
                })
                // .then(response => response.json())
                .then(data => {
                    // toastr.success(data.message);
                    checkAllDone();
                });
            });
        });

        // Cek apakah semua checkbox dicentang
function checkAllDone() {
    const all = document.querySelectorAll('.report-checkbox');
    const allChecked = [...all].length > 0 && [...all].every(c => c.checked);
    const doneButton = document.getElementById('done-button');
    if (doneButton) {
        doneButton.disabled = !allChecked;
    }
}


        checkAllDone();

        // Tombol Done
const doneButton = document.getElementById('done-button');
if (doneButton) {
    doneButton.addEventListener('click', function () {
        fetch(`/teknisi/report/check-all`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            toastr.success(data.message);
            checkAllDone();
        });
    });
}

    });

    
</script>
@endsection

