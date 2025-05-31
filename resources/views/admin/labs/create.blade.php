@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Laboratory Management</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Tambah Laboratorium</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <span class="text-gray-600 text-xl mdi mdi-flask-plus"></span>
                <h2 class="text-gray-800 text-l font-semibold ml-2">Tambah Laboratorium</h2>
            </div>
        </header>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Laboratorium</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route($role . '.lab.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Laboratorium</label>
                            <input type="text" name="lab_name" class="form-control @error('lab_name') is-invalid @enderror"
                                   value="{{ old('lab_name') }}" placeholder="Masukkan nama laboratorium" required>
                            @error('lab_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Teknisi Penanggung Jawab</label>
                            <select name="technician_id" class="form-control @error('technician_id') is-invalid @enderror" required>
                                <option value="">Pilih Teknisi</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('technician_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-flask-plus"></i> Simpan Laboratorium
                        </button>
                        <a href="{{ route($role . '.lab.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
