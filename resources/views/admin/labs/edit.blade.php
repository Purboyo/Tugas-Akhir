@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>{{ Auth::user()->role === 'admin' ? 'Admin' : 'Teknisi' }}</li>
            <li>Laboratory Management</li>
            <li>Edit Laboratory</li>
        </ul>
    </div>
</section>

<section class="section main-section">
    <div class="card">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon"><i class="mdi mdi-flask-edit"></i></span>
                <h2>Edit Laboratory</h2>
            </div>
        </header>
        <div class="card-content">
            <form action="{{ route($role. '.lab.update', $lab) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="field">
                    <label class="label">Laboratory Name</label>
                    <div class="control icons-left">
                        <input class="input" type="text" name="lab_name" placeholder="Laboratory Name" value="{{ old('lab_name', $lab->lab_name) }}" required>
                        <span class="icon is-small left"><i class="mdi mdi-flask"></i></span>
                    </div>
                    @error('lab_name')
                        <p class="help text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label">Technician</label>
                    <div class="control select">
                        <select name="technician_id" required>
                            <option value="">Select Technician</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician ->id }}" {{ old('technician_id', $lab->technician_id) == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('technician_id')
                        <p class="help text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="button blue">
                        <span class="icon"><i class="mdi mdi-content-save"></i></span>
                        <span>Update Laboratory</span>
                    </button>
                    <a href="{{ route($role. '.lab.index') }}" class="button red">
                        <span class="icon"><i class="mdi mdi-close"></i></span>
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection