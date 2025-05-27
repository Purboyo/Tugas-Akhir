@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>PC Management</li>
        </ul>
        <a href="{{ route('pc.create') }}" class="button blue">
            <span class="icon"><i class="mdi mdi-plus"></i></span>
            <span>Add PC</span>
        </a>
    </div>
</section>

<section class="section main-section">
    <form method="GET" action="{{ route('pc.index') }}" class="mb-4">
        <div class="card has-table">
            <header class="card-header">
                <div class="card-header-title">
                    <span class="icon"><i class="mdi mdi-desktop-classic"></i></span>
                    <h2>PC List</h2>
                </div>
                <div class="card-header-actions flex items-center space-x-2">
                    <!-- Search input -->
                    <div class="field has-addons mt-3">
                        <div class="control">
                            <input type="text" id="searchInput" class="input" placeholder="Search...">
                        </div>
                    </div>
                    <!-- Laboratory filter dropdown -->
                    <div class="control select">
                        <select name="lab_id" onchange="this.form.submit()">
                            <option value="">All Laboratories</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ $selectedLabId == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->lab_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(request()->has('lab_id') && request('lab_id'))
                        <div class="control">
                            <a href="{{ route('pc.index') }}" class="button is-light">Reset</a>
                        </div>
                    @endif
                </div>
            </header>

            <div class="px-4 py-2">
                <strong>Total PCs: <span id="pcCount">{{ $pcs->count() }}</span></strong>
            </div>

            <div class="card-content">
                <table class="table" id="pcTable">
                    <thead>
                        <tr>
                            <th>PC Name</th>
                            <th>Laboratory</th>
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pcs as $pc)
                        <tr>
                            <td>{{ $pc->pc_name }}</td>
                            <td>{{ $pc->lab->lab_name ?? 'N/A' }}</td>
                            <td>
                            <div class="p-4 border rounded">
                                {!! QrCode::size(150)->generate(route('form.qr.redirect', $pc->id)) !!}
                            </div>
                            </td>
                            <td class="actions-cell">
                                <div class="buttons right nowrap">
                                    <a href="{{ route('pc.edit', $pc->id) }}" class="button small blue">
                                        <span class="icon"><i class="mdi mdi-pencil"></i></span>
                                    </a>
                                    <form action="{{ route('pc.destroy', $pc->id) }}" method="POST" onsubmit="return confirm('Delete this PC?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button small red">
                                            <span class="icon"><i class="mdi mdi-delete"></i></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No PCs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</section>

<script>
    const searchInput = document.getElementById('searchInput');
    const pcCount = document.getElementById('pcCount');
    const rows = document.querySelectorAll('#pcTable tbody tr');

    function updatePcCount() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        pcCount.textContent = visibleRows.length;
    }

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();

        rows.forEach(row => {
            const pcNameCell = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            if (pcNameCell.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updatePcCount();
    });

    // Initialize PC count on page load
    updatePcCount();
</script>

@endsection
