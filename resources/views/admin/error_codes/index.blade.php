@extends('admin.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Error Code Management</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Manage error codes</small>
        </div>
        <div>
            <a href="{{ route($role . '.error-codes.create') }}" class="btn btn-outline-primary">
                <i class="fa fa-plus color-info"></i> Add Error Code
            </a>
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
                <span class="icon h2"><i class="mdi mdi-bug"> Error Code List</i></span>
            </div>
        </header>

        <div class="card-content">
            <table class="table text-dark" id="errorCodeTable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($errorCodes as $code)
                    <tr>
                        <td>{{ $code->code }}</td>
                        <td>{{ $code->description }}</td>
                        <td>
                            <a href="{{ route($role.'.error-codes.edit', $code) }}" class="btn btn-outline-warning mr-3" data-toggle="tooltip"
                                title="Edit">
                                <i class="fa fa-pencil color-muted"> Edit</i>
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal-{{ $code->id }}">
                                Delete
                            </button>
                            <div class="modal fade" id="deleteModal-{{ $code->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $code->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                      <h5 class="modal-title" id="deleteModalLabel-{{ $code->id }}">Confirm Delete</h5>
                                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Are you sure you want to delete error code <strong>{{ $code->code }}</strong> with description <strong>{{ $code->description }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                      <form action="{{ route($role . '.error-codes.destroy', $code) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-outline-danger">Yes, Delete</button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>      
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection