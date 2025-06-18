@extends('teknisi.app')
@section('title', 'Maintenance Form')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Maintenance - {{ $reminder->title }}</h2>
    <form action="{{ route('teknisi.maintenance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="reminder_id" value="{{ $reminder->id }}">
        <input type="hidden" name="laboratory_id" value="{{ $reminder->laboratory_id }}">
        <input type="hidden" name="user_id" value="{{ $reminder->user_id }}">

        @foreach($pcs as $pc)
            <div class="mb-4 border p-4 rounded">
                <h4 class="font-medium">{{ $pc->pc_name }}</h4>
                <input type="hidden" name="pcs[{{ $pc->id }}][pc_id]" value="{{ $pc->id }}">
                <div class="mt-2">
                    <label class="mr-4">
                        <input type="radio" name="pcs[{{ $pc->id }}][status]" value="Good" required>
                        Good
                    </label>
                    <label>
                        <input type="radio" name="pcs[{{ $pc->id }}][status]" value="Bad" required>
                        Bad
                    </label>
                </div>
            </div>
        @endforeach

        <div class="mb-4">
            <label for="note" class="block font-medium">General Note</label>
            <textarea name="note" id="note" class="form-control w-full" rows="3" placeholder="Optional note..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save"></i> Submit Maintenance
        </button>
    </form>
</div>
@endsection
