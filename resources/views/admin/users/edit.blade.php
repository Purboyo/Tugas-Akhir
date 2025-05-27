@extends('admin.app')

@section('content')

<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>User Management</li>
            <li>Edit User</li>
        </ul>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <span class="text-gray-600 text-2xl mr-2"><i class="mdi mdi-account-edit"></i></span>
                <h2 class="text-gray-800 text-xl font-semibold">Edit User: {{ $user->name }}</h2>
            </div>
        </header>
        <div class="p-6">
            <form action="{{ route('admin.user.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="field">
                        <label class="label text-gray-700">Full Name</label>
                        <div class="relative">
                            <input class="input border rounded-md w-full p-2" type="text" name="name" placeholder="John Doe" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="field">
                        <label class="label text-gray-700">Email</label>
                        <div class="relative">
                            <input class="input border rounded-md w-full p-2" type="email" name="email" placeholder="user@example.com" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="field">
                        <label class="label text-gray-700">Role</label>
                        <div class="relative">
                            <select name="role" class="input border rounded-md w-full p-2" required>
                                @foreach(['admin', 'teknisi', 'kepala_lab', 'jurusan'] as $role)
                                <option value="{{ $role }}" {{ (old('role', $user->role) == $role ? 'selected' : '') }}>
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
                        <label class="label text-gray-700">Password (Leave blank to keep current)</label>
                        <div class="relative">
                            <input class="input border rounded-md w-full p-2" type="password" name="password" placeholder="********">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-between mt-6">
                    <button type="submit" class="button green">
                        <span class="mr-2"><i class="mdi mdi-content-save"></i></span>
                        <span>Update User</span>
                    </button>
                    <a href="{{ route('admin.user.index') }}" class="button red">
                        <span class="mr-2"><i class="mdi mdi-close"></i></span>
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
            @if($user->id !== Auth::id())
            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.user.destroy', $user) }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="button" onclick="showDeleteToast('{{ $user->id }}', '{{ $user->name }}', '{{ ucfirst(str_replace('_', ' ', $user->role)) }}')" class="button red">
                    <span class="mr-2"><i class="mdi mdi-trash-can"></i></span>
                    <span>Delete</span>
                </button>
            </form>                         
            @endif
        </div>
    </div>
</section>




<script>
const toast = document.getElementById('toast');
const toastMessage = document.getElementById('toast-message');
const toastConfirm = document.getElementById('toast-confirm');
const toastCancel = document.getElementById('toast-cancel');

let currentDeleteFormId = null;

function showDeleteToast(userId, userName, userRole) {
  currentDeleteFormId = userId;
  toastMessage.textContent = `Yakin ingin menghapus user "${userName}" dengan role "${userRole}"?`;
  toast.classList.remove('hidden');
}

toastConfirm.addEventListener('click', () => {
  if(currentDeleteFormId) {
    document.getElementById('delete-form-' + currentDeleteFormId).submit();
  }
});

toastCancel.addEventListener('click', () => {
  toast.classList.add('hidden');
  currentDeleteFormId = null;
});

// Optional: auto-hide toast after 10 seconds if no action
setInterval(() => {
  if (!toast.classList.contains('hidden')) {
    toast.classList.add('hidden');
    currentDeleteFormId = null;
  }
}, 10000);
</script>

@endsection