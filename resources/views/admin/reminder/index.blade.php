@extends('admin.app')

@section('content')
@php
    $activeQuery = request()->except('active_page');
    $completedQuery = request()->except('completed_page');
@endphp

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Reminder Management</h1>
            <small class="text-muted">Admin · Reminder</small>
        </div>
        <div>
            <a href="{{ route('admin.reminder.create') }}" class="btn btn-outline-primary">
                <i class="fa fa-plus color-info"></i> Add Reminder
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

    {{-- Search --}}
    <div class="mb-3 mt-3 d-flex justify-content-end px-4">
        <form method="GET" action="{{ route('admin.reminder.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search title..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-magnify"></i></button>
        </form>
    </div>

    {{-- Active Reminders --}}
    <div class="card has-table mb-5">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon h2"><i class="mdi mdi-calendar-clock"></i> Active Reminders</span>
            </div>
        </header>

        <div class="card-content">
            <table class="table">
                <thead>
                    <tr class="text-dark">
                        <th>Title</th>
                        <th>Description</th>
                        <th>Reminder Date</th>
                        <th>Laboratory</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeReminders as $reminder)
                    <tr class="text-dark">
                        <td>{{ $reminder->title }}</td>
                        <td>{{ $reminder->description ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d M Y') }}</td>
                        <td>
                            {{ $reminder->laboratory->lab_name ?? '-' }}<br>
                            <small class="text-muted">{{ $reminder->laboratory->technician->name ?? '-' }}</small>
                        </td>
                        <td>
                            @php $status = $reminder->computed_status; @endphp
                            <span class="badge 
                                {{ $status === 'missed' ? 'bg-danger' : 'bg-warning' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="d-flex gap-2">
                            <form action="{{ route('admin.reminder.destroy', $reminder->id) }}" method="POST" onsubmit="return confirm('Delete this reminder?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger mr-3" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No active reminders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<div class="card-body">
    <nav>
        <ul class="pagination pagination-sm pagination-gutter justify-content-center">
            {{-- Previous Page --}}
            <li class="page-item {{ $activeReminders->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $activeReminders->previousPageUrl() ?? '#' }}">
                    <i class="icon-arrow-left"></i>
                </a>
            </li>

            {{-- Page Numbers --}}
            @for ($i = 1; $i <= $activeReminders->lastPage(); $i++)
                <li class="page-item {{ $i == $activeReminders->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $activeReminders->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next Page --}}
            <li class="page-item {{ !$activeReminders->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $activeReminders->nextPageUrl() ?? '#' }}">
                    <i class="icon-arrow-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>


    {{-- Completed Reminders --}}
    <div class="card has-table">
        <header class="card-header bg-light">
            <div class="card-header-title">
                <span class="icon h2"><i class="mdi mdi-check-circle-outline"></i> Completed Reminders</span>
            </div>
        </header>

        <div class="card-content">
            <table class="table table-striped">
                <thead>
                    <tr class="text-dark">
                        <th>Title</th>
                        <th>Description</th>
                        <th>Reminder Date</th>
                        <th>Laboratory</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedReminders as $reminder)
                    <tr class="text-dark">
                        <td>{{ $reminder->title }}</td>
                        <td>{{ $reminder->description ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d M Y') }}</td>
                        <td>
                            {{ $reminder->laboratory->lab_name ?? '-' }}<br>
                            <small class="text-muted">{{ $reminder->laboratory->technician->name ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-success">Completed</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No completed reminders.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<div class="card-body">
    <nav>
        <ul class="pagination pagination-sm pagination-gutter justify-content-center">
            {{-- Previous Page --}}
            <li class="page-item {{ $completedReminders->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $completedReminders->previousPageUrl() ?? '#' }}">
                    <i class="icon-arrow-left"></i>
                </a>
            </li>

            {{-- Page Numbers --}}
            @for ($i = 1; $i <= $completedReminders->lastPage(); $i++)
                <li class="page-item {{ $i == $completedReminders->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $completedReminders->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next Page --}}
            <li class="page-item {{ !$completedReminders->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $completedReminders->nextPageUrl() ?? '#' }}">
                    <i class="icon-arrow-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>

</section>
@endsection
