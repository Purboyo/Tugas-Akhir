@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">PC Management</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Manage PCs</small>
        </div>
        <div>
            <a href="{{ route(auth()->user()->role . '.pc.create') }}" class="btn btn-outline-primary">
                <i class="fa fa-plus color-info"></i> Add PC
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

    @php $role = auth()->user()->role; @endphp

    @forelse($labs as $lab)
        <div class="card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">{{ $lab->lab_name }}</h5>
                <span class="text-dark">Total PCs: {{ $lab->pcs_paginated->total() }}</span>
            </div>

            <div class="card-body">
                @if($lab->pcs_paginated->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered text-dark">
                            <thead>
                                <tr>
                                    <th>PC Name</th>
                                    <th>QR Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lab->pcs_paginated as $pc)
                                    <tr>
                                        <td>{{ $pc->pc_name }}</td>
                                        <td>
                                            <a href="{{ route('welcome', $pc->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                Lihat Halaman QR
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route($role . '.pc.edit', $pc) }}" class="btn btn-sm btn-outline-warning mr-2">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#deleteModal-{{ $pc->id }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>

                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="deleteModal-{{ $pc->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $pc->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel-{{ $pc->id }}">Confirm Delete</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete PC <strong>{{ $pc->pc_name }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route($role . '.pc.destroy', $pc) }}" method="POST">
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

                    {{-- Pagination --}}
                    <div class="card-body">
                        <nav>
                            <ul class="pagination pagination-sm pagination-gutter justify-content-center">
                                <li class="page-item {{ $lab->pcs_paginated->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $lab->pcs_paginated->previousPageUrl() ?? '#' }}"><i class="icon-arrow-left"></i></a>
                                </li>
                                @for ($i = 1; $i <= $lab->pcs_paginated->lastPage(); $i++)
                                    @php
                                        $query = request()->except("page_lab_$lab->id");
                                        $query["page_lab_$lab->id"] = $i;
                                    @endphp
                                    <li class="page-item {{ $i == $lab->pcs_paginated->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ url()->current() . '?' . http_build_query($query) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ !$lab->pcs_paginated->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $lab->pcs_paginated->nextPageUrl() ?? '#' }}"><i class="icon-arrow-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                @else
                    <p class="text-muted">Tidak ada PC pada lab ini.</p>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">Tidak ada data laboratorium.</div>
    @endforelse
</section>

@endsection
