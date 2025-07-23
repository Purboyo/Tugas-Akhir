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

    @forelse ($reports as $labName => $groupedReports)
    <div class="card mb-5" id="lab-{{ Str::slug($labName) }}">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-primary">{{ $labName }}</h5>
        </div>
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
                    @foreach ($groupedReports as $i => $report)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $report->reporter->name }}</td>
                        <td>{{ $report->reporter->npm }}</td>
                        <td>{{ $report->pc->pc_name }}</td>
                        <td>{{ $report->form->title }}</td>
                        <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @if(auth()->user()->role === 'teknisi')
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#statusModal-{{ $report->id }}">
                                <span class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"> {{ $report->status }}</i></span>
                            </a>
                            @else
                                {{ $report->status }}
                            @endif
                        </td>
                        @if(auth()->user()->role === 'teknisi')
                        <td class="text-center">
                            <input type="checkbox" 
                                   class="report-checkbox" 
                                   data-lab="{{ Str::slug($labName) }}"
                                   data-id="{{ $report->id }}" 
                                   {{ $report->checked ? 'checked' : '' }}>
                        </td>
                        <td>
                            <a href="{{ route('teknisi.report.show', $report->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="fa fa-eye"></i> Show
                            </a>
                            {{-- <a href="javascript:void(0)" data-toggle="modal" data-target="#deleteModal-{{ $report->id }}" class="btn btn-outline-danger btn-sm">
                                <i class="fa fa-close"></i> Delete
                            </a> --}}

                            {{-- Delete Modal --}}
                            {{-- <div class="modal fade" id="deleteModal-{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete report <strong>{{ $report->form->title }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                            <form method="POST" action="{{ route($role . '.report.destroy', $report->id) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- Status Modal --}}
                            <div class="modal fade" id="statusModal-{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('teknisi.report.updateStatus', $report->id) }}">
                                            @csrf @method('PATCH')
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Change Status</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="Good" {{ $report->status == 'Good' ? 'selected' : '' }}>Good</option>
                                                        <option value="Bad" {{ $report->status == 'Bad' ? 'selected' : '' }}>Bad</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Note (Optional)</label>
                                                    <textarea name="note" class="form-control" rows="3" placeholder="Add note...">{{ $report->note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-outline-info">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(auth()->user()->role === 'teknisi')
            <div class="mt-3 text-end">
                <button class="done-button btn btn-outline-success"
                        data-lab="{{ Str::slug($labName) }}"
                        disabled>
                    Done
                </button>
            </div>
            @endif

            @if(auth()->user()->role === 'teknisi' && $groupedReports->where('status', 'Bad')->count() > 0)
            <div class="mt-2 text-end">
                <a href="{{ route('teknisi.report.reportBadForm') }}?lab={{ $labName }}" 
                   class="btn btn-outline-secondary">
                    <i class="fa fa-paper-plane"></i> Send to Lab Head           </a>
            </div>
            @endif
        </div>
    </div>
    @empty
        <div class="alert alert-info text-center">No report found.</div>
    @endforelse
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.done-button').forEach(button => {
            checkLabDone(button.getAttribute('data-lab'));
        });

        document.querySelectorAll('.report-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const reportId = this.getAttribute('data-id');
                const labName = this.getAttribute('data-lab');
                const checked = this.checked;

                fetch(`/teknisi/report/${reportId}/check`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ checked: checked })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Request failed');
                    // return response.json();
                        checkLabDone(labName);
                })
                .then(data => {
                    this.checked = checked;
                    checkLabDone(labName);
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !checked;
                });
            });
        });

        document.querySelectorAll('.done-button').forEach(button => {
            button.addEventListener('click', function () {
                const labName = this.getAttribute('data-lab');
                const reportIds = Array.from(document.querySelectorAll(`.report-checkbox[data-lab="${labName}"]`))
                    .map(cb => cb.getAttribute('data-id'));

                fetch(`/teknisi/report/check-all`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ lab_name: labName, report_ids: reportIds })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    toastr.success(data.message);
                    button.disabled = true;
                
                    // Tandai semua checkbox sebagai checked
                    document.querySelectorAll(`.report-checkbox[data-lab="${labName}"]`).forEach(cb => cb.checked = true);
                
                    // Reload halaman setelah 1.5 detik
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                })        
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Gagal menyimpan perubahan');
                });
            });
        });

        function checkLabDone(labName) {
            const checkboxes = document.querySelectorAll(`.report-checkbox[data-lab="${labName}"]`);
            const doneButton = document.querySelector(`.done-button[data-lab="${labName}"]`);
            if (!doneButton) return;

            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            doneButton.disabled = !allChecked;
        }
    });
</script>
@endsection
