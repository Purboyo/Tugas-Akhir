@extends('admin.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">User Management</h1>
            <small class="text-muted">Admin · Add New User</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <h2 class="text-gray-800 text-l font-semibold">Add New User</h2>
            </div>
        </header>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form New User</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 text-dark">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group col-md-6 text-dark">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group col-md-6 text-dark">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6 text-dark">
                                <label>Role</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="" disabled selected>Select Role</option>
                                    @foreach(['admin', 'teknisi', 'kepala_lab', 'jurusan'] as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-account-plus"></i> Save User</button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary"><i class="mdi mdi-arrow-left"></i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
