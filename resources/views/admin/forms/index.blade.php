@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>Form Management</li>
        </ul>
        <div class="flex items-center space-x-2">
            <a href="{{ route($role . '.form.create') }}" class="button blue">
                <span class="icon"><i class="mdi mdi-plus"></i></span>
                <span>Add New Form</span>
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
                <span class="icon"><i class="mdi mdi-file-document-box"></i></span>
                <h2>Forms List</h2>
            </div>
            <div class="card-header-actions">
                <div class="field has-addons items-center">
                    <div class="control">
                        <input type="text" id="searchInput" class="input" placeholder="Search by Title...">
                    </div>
                </div>
            </div>
            <div class="control">
                <div class="select">
                    <select id="labFilter">
                        <option value="">All Labs</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->lab_name }}">{{ $lab->lab_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </header>

        <div class="px-4 py-2">
            <strong>Total forms: <span id="formCount">{{ count($forms) }}</span></strong>
        </div>

        <div class="card-content">
            <table class="table" id="formTable">
                <thead>
                    <tr>
                        <th>Form Title</th>
                        <th>Laboratory</th>
                        <th>Questions Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forms as $form)
                    <tr>
                        <td>{{ $form->title }}</td>
                        <td class="lab-cell">{{ $form->lab->lab_name ?? 'N/A' }}</td>
                        <td>{{ $form->questions->count() }}</td>
                        <td class="actions-cell">
                            <div class="buttons right nowrap">
                                <a href="{{ route($role . '.form.edit', $form) }}" class="button small blue">
                                    <span class="icon"><i class="mdi mdi-pencil"></i></span>
                                </a>
                                <form action="{{ route($role . '.form.destroy', $form) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button small red" onclick="return confirm('Are you sure you want to delete this form?');">
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
    const labFilter = document.getElementById('labFilter');
    const formCount = document.getElementById('formCount');
    const rows = document.querySelectorAll('#formTable tbody tr');

    function filterTable() {
        const titleFilter = searchInput.value.toLowerCase();
        const selectedLab = labFilter.value.toLowerCase();

        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const lab = row.querySelector('.lab-cell').textContent.toLowerCase();

            const matchTitle = title.includes(titleFilter);
            const matchLab = selectedLab === '' || lab === selectedLab;

            if (matchTitle && matchLab) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        formCount.textContent = visibleCount;
    }

    searchInput.addEventListener('input', filterTable);
    labFilter.addEventListener('change', filterTable);

    // Initial count
    filterTable();
</script>

@endsection
