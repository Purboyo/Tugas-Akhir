@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>{{ Auth::user()->role === 'admin' ? 'Admin' : 'Teknisi' }}</li>
            <li>Laboratory Management</li>
        </ul>
        <div class="flex items-center space-x-2">
            <a href="{{ route($role . '.lab.create') }}" class="button blue">
                <span class="icon"><i class="mdi mdi-plus"></i></span>
                <span>Add Laboratory</span>
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
                <span class="icon"><i class="mdi mdi-flask"></i></span>
                <h2>Laboratory List</h2>
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
            <strong>Total laboratories: <span id="labCount">{{ count($labs) }}</span></strong>
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
                    @foreach($labs as $lab)
                    <tr>
                        <td>{{ $lab->lab_name }}</td>
                        <td>{{ $lab->technician->name ?? 'N/A' }}</td>
                        <td class="actions-cell">
                            <div class="buttons right nowrap">
                                <a href="{{ route($role . '.lab.edit', $lab) }}" class="button small blue --jb-modal" data-target="sample-modal">
                                    <span class="icon"><i class="mdi mdi-pencil"></i></span>
                                </a>
                                <form action="{{ route($role . '.lab.destroy', $lab) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button small red" onclick="return confirm('Are you sure you want to delete this laboratory?');">
                                        <span class="icon"><i class="mdi mdi-delete"></i></span>
                                    </button>
                                </form>
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
    const labCount = document.getElementById('labCount');
    const rows = document.querySelectorAll('#labTable tbody tr');

    function updateLabCount() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        labCount.textContent = visibleRows.length;
    }

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();

        rows.forEach(row => {
            const labNameCell = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            if (labNameCell.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateLabCount();
    });

    // Initialize lab count on page load
    updateLabCount();
</script>

@endsection