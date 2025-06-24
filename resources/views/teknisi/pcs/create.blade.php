@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">PC Management</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Add PC</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <h2 class="text-gray-800 text-l font-semibold ml-2">Add PC</h2>
            </div>
        </header>
        <div class="card text-dark">
            <div class="card-header">
                <h4 class="card-title">Form Add PC</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route($role . '.pc.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>PC Name</label>
                            <input type="text" name="pc_name" class="form-control @error('pc_name') is-invalid @enderror"
                                   value="{{ old('pc_name') }}" placeholder="Enter PC name" required>
                            @error('pc_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Laboratory</label>
                            <select name="lab_id" class="form-control @error('lab_id') is-invalid @enderror" required>
                                <option value="">Select Laboratory</option>
                                @foreach($labs as $lab)
                                    <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->lab_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lab_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-outline-success">
                            <i class="mdi mdi-content-save"></i> Save PC
                        </button>
                        <a href="{{ route($role . '.pc.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
