@extends('admin.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <ul>
      <li>Admin</li>
      <li>Dashboard</li>
    </ul>
  </div>
</section>

{{-- Main Content --}}
  <section class="section main-section">
    <section class="section main-section">
    </section>
    <div class="grid gap-6 grid-cols-1 md:grid-cols-3 mb-6">
      <div class="card">
        <div class="card-content">
          <div class="flex items-center justify-between">
            <div class="widget-label">
              <h3>Clients</h3>
              <h1>512</h1>
            </div>
            <span class="icon widget-icon text-green-500"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
          </div>
        </div>
      </div>
      <!-- More cards here -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Reminder Hari Ini</h4>
        </div>
        <div class="card-body">
          <ul class="list-group">
          @forelse($reminders as $reminder)
          <li class="list-group-item d-flex justify-content-between align-items-center bg-info text-white">
            <strong>{{$reminder->user->name}} : {{ $reminder->title }}</strong> {{ $reminder->reminder_date->format('d M Y') }}
            @if(Auth::user()->role === 'admin')
            <form action="{{ route('admin.reminders.destroy', $reminder->id) }}" method="POST">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-light">Hapus</button>
            </form>
            @endif
          </li>
          @empty
          <li class="list-group-item">Tidak ada reminder</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</section>

@endsection