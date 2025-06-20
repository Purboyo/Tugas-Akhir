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
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon h2"><i class="mdi mdi-form-select"> Form List</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route($role . '.form.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2 p-3">
            <strong>Total Form: {{ count($forms) }}</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="formTable">
                <thead class="thead-light">
                    <tr>
                        <th>Form Title</th>
                        <th>Laboratory</th>
                        <th>Number of Questions</th>
                        <th>Form Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                    <tr>
                        <td>{{ $form->title }}</td>
                        <td>{{ $form->laboratories->pluck('lab_name')->join(', ') }}</td>
                        <td>{{ $form->questions->count() }}</td>
                        <td>
                            @if($form->is_default)
                                <span class="badge badge-info">Default (Admin)</span>
                            @else
                                <span class="badge badge-secondary">Custom</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if(!$form->is_default || auth()->user()->role === 'admin')
                                <a href="{{ route($role . '.form.edit', $form) }}" class="mr-3" data-toggle="tooltip" title="Edit">
                                    <i class="fa fa-pencil color-muted"> Edit</i>
                                </a>
                                <a href="javascript:void(0)" title="Delete" data-toggle="modal" data-target="#deleteModal-{{ $form->id }}">
                                    <i class="fa fa-close"></i> Delete
                                </a>
                                @else
                                <span class="text-muted">No Action</span>
                                @endif
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
                        <td colspan="5" class="text-center text-muted">No form data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>

@endsection