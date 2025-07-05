@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('title', 'Maintenance Form')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Maintenance - {{ $reminder->title }}</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Submit maintenance details</small>
        </div>
    </div>
</section>

<section class="section main-section text-dark">
    <div class="card has-table shadow-sm">
        <div class="card-content px-4 py-4">
            <form action="{{ route('teknisi.maintenance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="reminder_id" value="{{ $reminder->id }}">
                <input type="hidden" name="laboratory_id" value="{{ $reminder->laboratory_id }}">
                <input type="hidden" name="user_id" value="{{ $reminder->user_id }}">

                <div class="mb-4">
                    <div class="row">
                        @foreach($pcs as $pc)
                            <div class="col-md-3 mb-3">
                                <div class="border p-4 rounded h-100">
                                    <h4 class="font-medium mb-3">{{ $pc->pc_name }}</h4>
                                    <input type="hidden" name="pcs[{{ $pc->id }}][pc_id]" value="{{ $pc->id }}">

                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-success active mr-3">
                                            <input type="radio" name="pcs[{{ $pc->id }}][status]" value="Good" autocomplete="off" checked> Good
                                        </label>
                                        <label class="btn btn-outline-danger">
                                            <input type="radio" name="pcs[{{ $pc->id }}][status]" value="Bad" autocomplete="off"> Bad
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                <div class="mb-4 mt-4">
                    <div class="col-md-8 mx-auto">
                        <label for="note" class="form-label fw-bold mb-2 text-dark">General Note</label>
                        <textarea name="note" id="note" class="form-control" rows="3" placeholder="Optional note..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mr-3">
                        <i class="mdi mdi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-outline-primary px-4">
                        <i class="mdi mdi-content-save mr-1"></i> Submit Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
