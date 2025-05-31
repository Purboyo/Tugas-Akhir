@extends('admin.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">User Management</h1>
            <small class="text-muted">{{ ucfirst($role) }} · Manage system users</small>
        </div>
        <div>
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
            <i class="fa fa-plus color-info"></i>  Add
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
                <span class="icon h2"><i class="mdi mdi-account-multiple">  User List</i></span>            </div>
            <div class="card-header-actions ">
                <form method="GET" action="{{ route('admin.user.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>

        <div class="px-4 py-2">
            <strong>Total users: <span id="userCount">{{ count($users) }}</span></strong>
        </div>

        <div class="card-content">
            <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="flex items-center">
                        <div class="image-cell">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                    class="rounded-full w-10 h-10" alt="Avatar {{ $user->name }}">
                                <span>{{ $user->name }}</span>
                            </div>
                        </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="tag {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} rounded-full px-3 py-1 text-sm">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td><span>
                            <a href="{{route ('admin.user.edit', $user)}}" class="mr-4 " data-toggle="tooltip"
                            data-placement="top" title="Edit"><i
                            class="fa fa-pencil color-muted">  Edit</i>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">
                <nav>
                    <ul class="pagination pagination-sm pagination-gutter">
                        {{-- Previous Page --}}
                        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}">
                                <i class="icon-arrow-left"></i>
                            </a>
                        </li>
            
                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
            
                        {{-- Next Page --}}
                        <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}">
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

