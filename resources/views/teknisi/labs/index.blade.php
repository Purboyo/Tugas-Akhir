@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

{{-- Page Heading --}}
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Laboratory Management</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Manage laboratories</small>
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

    <div class="card has-table">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon h2"><i class="mdi mdi-google-classroom"> Laboratory List</i></span>
            </div>
            <div class="card-header-actions">
                <form method="GET" action="{{ route($role . '.lab.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2 shadow-sm" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-magnify"></i></button>
                </form>
            </div>
        </header>

        <div class="px-4 py-2 text-dark">
            <strong>Total laboratories: <span id="labCount">{{ $labs->total() }}</span></strong>
        </div>

        <div class="card-content">
<div class="card-content px-4 py-3">
    <div class="row">
        @forelse($labs as $lab)
        <div class="col-md-6 col-lg-4 mb-4 ">
            <div class="card shadow-lg border rounded-4 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title text-bold">{{ $lab->lab_name }}</h5>
                        <p class="mb-1 text-dark"><strong>Technician:</strong> {{ $lab->technician->name ?? 'N/A' }}</p>
                        <p class="mb-0 text-dark"><strong>Total PCs:</strong> {{ $lab->pcs->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No laboratory data found.
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $labs->links('pagination::bootstrap-4') }}
    </div>
</div>

        </div>
    </div>
</section>


@endsection
