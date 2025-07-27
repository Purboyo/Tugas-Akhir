@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Edit Error Code</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Edit the error code</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <h2 class="text-gray-800 text-l font-semibold ml-2">Edit Error Code</h2>
            </div>
        </header>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Error Code</h4>
            </div>
            <div class="card-body">
                <div class="basic-form text-dark">
                    <form method="POST" action="{{ route($role . '.error-codes.update', $errorCode) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control" value="{{ $errorCode->code }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                   value="{{ old('description', $errorCode->description) }}" required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-outline-success">
                            <i class="mdi mdi-check"></i> Update
                        </button>
                        <a href="{{ route($role . '.error-codes.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
