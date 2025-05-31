@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Form Management</h1>
            <small class="text-muted">{{ ucfirst($role) }} · Management Formulir</small>
        </div>
        <a href="{{ route($role . '.form.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i> Add Form
        </a>
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
    
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2 p-3">
                <div class="form-inline d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari judul form..." onkeyup="filterForms()">
                    <select id="labFilter" class="form-select" onchange="filterForms()">
                        <option value="">All</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->lab_name }}">{{ $lab->lab_name }}</option>
                        @endforeach
                    </select>
                </div>
                <strong>Total Form: <span id="formCount">{{ count($forms) }}</span></strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="formTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Form Title</th>
                            <th>Laboratory</th>
                            <th>Number of Questions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($forms as $form)
                        <tr>
                            <td>{{ $form->title }}</td>
                            <td class="lab-cell">{{ $form->lab->lab_name ?? 'N/A' }}</td>
                            <td>{{ $form->questions->count() }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route($role . '.form.edit', $form) }}" class="mr-3" data-toggle="tooltip"
                                        title="Edit">
                                        <i class="fa fa-pencil color-muted"> Edit</i>
                                    </a>
                                    <a href="javascript:void(0)" title="Delete"
                                    data-toggle="modal" data-target="#deleteModal-{{ $form->id }}">
                                        <i class="fa fa-close"></i> Delete
                                    </a>   
                                </div>
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $form->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                          <h5 class="modal-title" id="deleteModalLabel-{{ $form->id }}">Confirm Delete</h5>
                                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          Are you sure you want to delete this form <strong>{{ $form->title }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <form action="{{ route($role . '.form.destroy', $form) }}" method="POST">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="btn btn-danger">Delete</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No form data found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

<script>
    const searchInput = document.getElementById('searchInput');
    const labFilter = document.getElementById('labFilter');
    const formCount = document.getElementById('formCount');
    const rows = document.querySelectorAll('#formTable tbody tr');

    function filterTable() {
        const titleFilter = searchInput.value.toLowerCase();
        const selectedLab = labFilter.value.toLowerCase();
        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const lab = row.querySelector('.lab-cell').textContent.toLowerCase();

            const matchTitle = title.includes(titleFilter);
            const matchLab = selectedLab === '' || lab === selectedLab;

            if (matchTitle && matchLab) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        formCount.textContent = visibleCount;
    }

    searchInput.addEventListener('input', filterTable);
    labFilter.addEventListener('change', filterTable);
    filterTable();
</script>

@endsection
