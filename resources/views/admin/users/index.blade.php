@extends('admin.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>User Management</li>
        </ul>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.user.create') }}" class="button blue">
                <span class="icon"><i class="mdi mdi-plus"></i></span>
                <span>Add User</span>
            </a>
        </div>
    </div>
</section>

<section class="section main-section">
    @if(session('success'))
        <div class="notification green">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
                <div>
                    <span class="icon"><i class="mdi mdi-check-circle"></i></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="button small textual --jb-notification-dismiss" onclick="this.closest('.notification').style.display='none';">
                    Dismiss
                </button>
            </div>
        </div>
    @endif

    <div class="card has-table">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                <h2>User List</h2>
            </div>
            <div class="card-header-actions">
                <div class="field has-addons">
                    <div class="control">
                        <input type="text" id="searchInput" class="input" placeholder="Search...">
                    </div>
                </div>
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
                        <td class="actions-cell">
                            <div class="buttons right nowrap">
                                <a href="{{ route('admin.user.edit', $user) }}" class="button small blue --jb-modal" data-target="sample-modal">
                                    <span class="icon"><i class="mdi mdi-pencil"></i></span>
                                </a>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    const searchInput = document.getElementById('searchInput');
    const userCount = document.getElementById('userCount');
    const rows = document.querySelectorAll('#userTable tbody tr');

    function updateUserCount() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        userCount.textContent = visibleRows.length;
    }

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();

        rows.forEach(row => {
            const nameCell = row.querySelector('td:nth-child(1) span').textContent.toLowerCase();
            if (nameCell.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateUserCount();
    });

    // Initialize user count on page load
    updateUserCount();
</script>

@endsection

