@extends('admin.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Reminder Management</h1>
            <small class="text-muted">Admin · Jadwal & Pengingat</small>
        </div>
        <div>
            <a href="{{ route('admin.reminder.create') }}" class="btn btn-primary">
                <i class="fa fa-plus color-info"></i> Tambah Reminder
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
                <span class="icon h2"><i class="mdi mdi-calendar-clock"> Daftar Reminder</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route('admin.reminder.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search judul..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>

        <div class="px-4 py-2">
            <strong>Total reminders: <span id="reminderCount">{{ count($reminders) }}</span></strong>
        </div>

        <div class="card-content">
            <table class="table" id="reminderTable">
                <thead>
                    <tr class="text-dark">
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Reminder</th>
                        <th>Teknisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <a href="{{ route('admin.reminder.edit', $reminder->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pencil"></i> Edit
                            </a>

                            <form action="{{ route('admin.reminder.destroy', $reminder->id) }}" method="POST" onsubmit="return confirm('Hapus reminder ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" data-toggle="tooltip" title="Hapus">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data reminder.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</section>

@endsection
