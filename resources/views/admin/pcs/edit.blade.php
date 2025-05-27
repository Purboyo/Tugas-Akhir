@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>PC Management</li>
            <li>Edit PC</li>
        </ul>
    </div>
</section>

<section class="section main-section">
    <div class="card">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon"><i class="mdi mdi-desktop-classic"></i></span>
                <h2>Edit PC</h2>
            </div>
        </header>
        <div class="card-content">
            <form action="{{ route('pc.update', $pc->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="field">
                    <label class="label">PC Name</label>
                    <div class="control icons-left">
                        <input class="input" type="text" name="pc_name" value="{{ old('pc_name', $pc->pc_name) }}" required>
                        <span class="icon is-small left"><i class="mdi mdi-desktop-classic"></i></span>
                    </div>
                    @error('pc_name')
                        <p class="help text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label">Laboratory</label>
                    <div class="control select">
                        <select name="lab_id" required>
                            <option value="">Select Laboratory</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('lab_id', $pc->lab_id) == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->lab_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('lab_id')
                        <p class="help text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="button blue">
                        <span class="icon"><i class="mdi mdi-content-save-edit"></i></span>
                        <span>Update PC</span>
                    </button>
                    <a href="{{ route('pc.index') }}" class="button red">
                        <span class="icon"><i class="mdi mdi-close"></i></span>
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
