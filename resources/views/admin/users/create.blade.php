@extends('admin.app')
@section('content')

<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul class="flex space-x-4">
            <li class="text-gray-600">Admin</li>
            <li class="text-gray-600">User  Management</li>
            <li class="text-gray-800 font-semibold">Create User</li>
        </ul>
    </div>
</section>
<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <span class="text-gray-600 text-2xl mr-2"><i class="mdi mdi-account-plus"></i></span>
                <h2 class="text-gray-800 text-xl font-semibold">Create New User</h2>
            </div>
        </header>
        <div class="p-6">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf  
                <div class="grid grid-cols-1 gap-4">
                    <div class="field">
                        <label class="label text-gray-700">Full Name</label>
                        <div class="relative">
                            <input class="input border rounded-md w-full p-2" type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500"><i class="mdi mdi-account"></i></span>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="label text-gray-700">Email</label>
                        <div class="relative">
                            <input class="input border rounded-md w-full p-2" type="email" name="email" placeholder="user@example.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div class="field">
                        <label class="label text-gray-700">Role</label>
                        <div class="relative">
                            <select name="role" class="input border rounded-md w-full p-2" required>
                                <option value="">Pilih Role</option>
                                @foreach(['admin', 'teknisi', 'kepala_lab', 'jurusan'] as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror                        
                    </div>

                    <div class="field">
                        <label class="label text-gray-700">Password</label>
                        <div class="relative">
                            <input class="input border rounded-md w-full p-2" type="password" name="password" placeholder="********" required>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>        
                <div class="flex justify-between mt-6">
                    <button type="submit" class="button blue">
                        <span class="mr-2"><i class="mdi mdi-content-save"></i></span>
                        <span>Create User</span>
                    </button>
                    <a href="{{ route('admin.user.index') }}" class="button red">
                        <span class="mr-2"><i class="mdi mdi-close"></i></span>
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection