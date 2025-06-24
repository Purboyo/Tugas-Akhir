@extends('admin.app')

@section('content')

{{-- Page Heading --}}
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
    {{-- Toastr Notif --}}
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
                <span class="icon h2"><i class="mdi mdi-calendar-clock"> Reminder List</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route('admin.reminder.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search title..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>

        <div class="px-4 py-2 text-dark">
            <strong>Total reminders: <span id="reminderCount">{{ count($reminders) }}</span></strong>
        </div>

        <div class="card-content">
            <table class="table" id="reminderTable">
                <thead>
                    <tr class="text-dark">
                        <th>Title</th>
                        <th>Description</th>
                        <th>Reminder Date</th>
                        <th>Technician</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminders as $reminder)
                    <tr class="text-dark">
                        <td>{{ $reminder->title }}</td>
                        <td>{{ $reminder->description ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d M Y') }}</td>
                        <td>{{ optional($reminder->user)->name ?? '-' }}</td>
                        <td>
                            @php
                                $status = $reminder->computed_status; // dari accessor
                            @endphp
                            <span class="badge 
                                {{ $status === 'completed' ? 'bg-success' : 
                                ($status === 'missed' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.reminder.edit', $reminder->id) }}" class="btn btn-outline-warning mr-3" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pencil"></i> Edit
                            </a>

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
                        <td colspan="6" class="text-center text-muted">No data reminder.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</section>

@endsection
