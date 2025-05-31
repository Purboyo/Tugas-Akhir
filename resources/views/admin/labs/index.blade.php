@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Laboratory Management</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Manage laboratories</small>
        </div>
        <div>
            <a href="{{ route($role . '.lab.create') }}" class="btn btn-primary">
                <i class="fa fa-plus color-info"></i> Add
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
                <span class="icon h2"><i class="mdi mdi-google-classroom"> Laboratory List</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route($role . '.lab.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>

        <div class="px-4 py-2">
            <strong>Total laboratories: <span id="labCount">{{ $labs->total() }}</span></strong>
        </div>

        <div class="card-content">
            <table class="table" id="labTable">
                <thead>
                    <tr>
                        <th>Laboratory Name</th>
                        <th>Technician</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($labs as $lab)
                    <tr>
                        <td>{{ $lab->lab_name }}</td>
                        <td>{{ $lab->technician->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route($role . '.lab.edit', $lab) }}" class="mr-3" data-toggle="tooltip"
                                title="Edit">
                                <i class="fa fa-pencil color-muted"> Edit</i>
                            </a>
                            <a href="javascript:void(0)" title="Delete"
                               data-toggle="modal" data-target="#deleteModal-{{ $lab->id }}">
                                <i class="fa fa-close"></i> Delete
                            </a>                    
                            <!-- Modal untuk tiap lab -->
                            <div class="modal fade" id="deleteModal-{{ $lab->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $lab->id }}" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="deleteModalLabel-{{ $lab->id }}">Confirm Delete</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    Are you sure you want to delete laboratory <strong>{{ $lab->lab_name }}</strong>?
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <form action="{{ route($role . '.lab.destroy', $lab) }}" method="POST">
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
                        <td colspan="3" class="text-center">No laboratory data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="card-body">
                <nav>
                    <ul class="pagination pagination-sm pagination-gutter">
                        {{-- Previous Page --}}
                        <li class="page-item {{ $labs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $labs->previousPageUrl() }}">
                                <i class="icon-arrow-left"></i>
                            </a>
                        </li>

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $labs->lastPage(); $i++)
                        <li class="page-item {{ $i == $labs->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $labs->url($i) }}">{{ $i }}</a>
                        </li>
                        @endfor

                        {{-- Next Page --}}
                        <li class="page-item {{ !$labs->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $labs->nextPageUrl() }}">
                                <i class="icon-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>


@endsection
