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
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                </form> 
            </div>
        </header>

        <div class="card-content px-4 py-2">
            <table class="table is-fullwidth is-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>ID</th>
                        <th>PC</th>
                        <th>Form</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Checklist</th>
                        <th>Action</th>
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
    <select class="form-select status-dropdown" data-id="{{ $report->id }}">
        <option value="baik" {{ $report->status == 'baik' ? 'selected' : '' }}>Baik</option>
        <option value="rusak" {{ $report->status == 'rusak' ? 'selected' : '' }}>Rusak</option>
        <option value="perbaikan" {{ $report->status == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
    </select>
</td>
                            <td>
                                <input type="checkbox" class="report-checkbox" data-id="{{ $report->id }}"
                                    {{ $report->checked ? 'checked' : '' }}>
                            </td>                 
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
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route($role . '.report.destroy', $report->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 text-end">
                <button id="done-button" class="btn btn-success" disabled>Done</button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.report-checkbox');
        const doneBtn = document.getElementById('done-button');

        function updateDoneButtonState() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            doneBtn.disabled = !allChecked;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const reportId = cb.dataset.id;

                // Simpan status checked ke backend
                fetch(`/teknisi/report/check/${reportId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ checked: cb.checked })
                });

                updateDoneButtonState();
            });
        });

        updateDoneButtonState();

        doneBtn.addEventListener('click', () => {
            if (confirm('Selesaikan verifikasi semua report dan masukkan ke histori?')) {
                fetch('/teknisi/report/done', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).then(res => location.reload());
            }
        });
        document.querySelectorAll('.change-status').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const reportId = this.dataset.id;
                const newStatus = this.dataset.status;
                
                fetch(`/teknisi/report/status/${reportId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                }).then(() => location.reload());
            });
        });
    });
</script>
@endpush

@endsection

