@extends('teknisi.app')
@section('title', 'Maintenance')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5 fw-semibold fs-2">Maintenance Lab</h2>

    @php
        $reminderBisa = [];
        $reminderTidakBisa = [];

        foreach ($reminders as $reminder) {
            $canDoMaintenance = now()->toDateString() >= \Carbon\Carbon::parse($reminder->reminder_date)->toDateString()
                                && is_null($reminder->historyMaintenance);

            if ($canDoMaintenance) {
                $reminderBisa[] = $reminder;
            } else {
                $reminderTidakBisa[] = $reminder;
            }
        }
    @endphp

    {{-- Ready for Maintenance Reminders --}}
    <h3 class="fs-4 fw-bold mb-4">Reminders Ready for Maintenance</h3>
    <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
        @forelse ($reminderBisa as $reminder)
            <div class="col">
                <div class="card shadow-sm h-100 border-0" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">{{ $reminder->title }}</h5>
                        <p class="card-text text-secondary">{{ $reminder->description }}</p>
                        <p class="mb-1 text-muted"><small>Lab: {{ $reminder->laboratory->lab_name }}</small></p>
                        <p class="mb-3 text-muted"><small>Reminder Date: {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d M Y') }}</small></p>

                        <a href="{{ route('teknisi.maintenance.create', $reminder->id) }}" 
                           class="btn btn-primary d-inline-flex align-items-center">
                            <span class="material-icons md-18 me-2">build</span> Perform Maintenance
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <p class="text-center text-muted fst-italic">There are no reminders available for maintenance at this time.</p>
            </div>
        @endforelse
    </div>

    {{-- Not Active Yet / Completed Reminders --}}
    <h3 class="fs-4 fw-bold mb-4">Inactive or Completed Reminders</h3>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @forelse ($reminderTidakBisa as $reminder)
            <div class="col">
                <div class="card bg-light shadow-sm h-100 border-0" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <h5 class="card-title text-dark fw-semibold">{{ $reminder->title }}</h5>
                        <p class="card-text text-secondary">{{ $reminder->description }}</p>
                        <p class="mb-1 text-muted"><small>Lab: {{ $reminder->laboratory->lab_name }}</small></p>
                        <p class="mb-3 text-muted"><small>Reminder Date: {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d M Y') }}</small></p>

                        @if(!is_null($reminder->historyMaintenance))
                            <button type="button" class="btn btn-light mt-3 d-inline-flex align-items-center opacity-50" disabled aria-disabled="true" tabindex="-1">
                                <span class="material-icons md-18 me-2"></span> Maintenance Completed
                            </button>
                        @else
                            <button type="button" class="btn btn-dark mt-3 d-inline-flex align-items-center opacity-50" disabled aria-disabled="true" tabindex="-1">
                                <span class="material-icons md-18 me-2"></span> Maintenance Not Yet Available
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <p class="text-center text-muted fst-italic">No other reminders available.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Optional small hover effect --}}
<style>
    .card:hover {
        transform: scale(1.03);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
</style>
@endsection

