@extends('admin.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">User Management</h1>
            <small class="text-muted">Admin · Manage system users</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <h2 class="text-gray-800 text-l font-semibold">Edit User: {{ $user->name }}</h2>
            </div>
        </header>
        <div class="card">
                <div class="card-body">
                    <div class="basic-form">
                        <form action="{{ route('admin.user.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name', $user->name)}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{old('email', $user->email)}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password (leave blank to keep current)">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Role</label>
                                    <select id="role" name="role" class="form-control">
                                        @foreach(['admin', 'teknisi', 'kepala_lab', 'jurusan'] as $role)
                                        <option value="{{ $role }}" {{ (old('role', $user->role) == $role ? 'selected' : '') }}>
                                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Update User</button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary"><i class="mdi mdi-arrow-left"></i> Cancel</a>
                        </form>
                        @if($user->id !== Auth::id())
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.user.destroy', $user) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <!-- Trigger Button -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $user->id }}">
                                <span class="mr-2"><i class="mdi mdi-trash-can"></i></span>
                                <span>Delete</span>
                            </button>  
                        </form>                         
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
           
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">
                Confirm Delete
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this user?
            <ul class="mt-2">
                <li><strong>Name:</strong> {{ $user->name }}</li>
                <li><strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role)) }}</li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <form action="{{ route('admin.user.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Yes, delete it</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
@endsection