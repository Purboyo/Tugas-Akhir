@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>User</li>
            <li>Form Laporan</li>
            <li>Isi Form</li>
        </ul>
    </div>
</section>

<section class="section main-section">
    <div class="card">
        <header class="card-header">
            <div class="card-header-title">
                <span class="icon"><i class="mdi mdi-form-select"></i></span>
                <h2>{{ $form->title }}</h2>
            </div>
        </header>

        <div class="card-content">
            @if(session('success'))
                <div class="notification is-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('form.submit', $form) }}" method="POST">
                @csrf

                {{-- Optional: Hidden PC ID --}}
                <input type="hidden" name="pc_id" value="{{ request()->query('pc') }}">

                <div class="field">
                    <label class="label">Nama</label>
                    <div class="control icons-left">
                        <input class="input" type="text" name="reporter[name]" value="{{ old('reporter.name') }}" required>
                        <span class="icon is-small left"><i class="mdi mdi-account"></i></span>
                    </div>
                    @error('reporter.name')
                        <p class="help text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label">NPM/NIP/ID</label>
                    <div class="control icons-left">
                        <input class="input" type="text" name="reporter[npm]" value="{{ old('reporter.npm') }}" required>
                        <span class="icon is-small left"><i class="mdi mdi-card-account-details"></i></span>
                    </div>
                    @error('reporter.npm')
                        <p class="help text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @foreach($form->questions as $question)
                    <div class="field">
                        <label class="label">{{ $question->question_text }}</label>
                        @php
                            $name = "answers[{$question->id}]";
                            $old = old("answers.{$question->id}");
                        @endphp

                        @switch($question->type)
                            @case('text')
                                <div class="control">
                                    <input class="input" type="text" name="{{ $name }}" value="{{ $old }}">
                                </div>
                                @break

                            @case('number')
                                <div class="control">
                                    <input class="input" type="number" name="{{ $name }}" value="{{ $old }}">
                                </div>
                                @break

                            @case('textarea')
                                <div class="control">
                                    <textarea class="textarea" name="{{ $name }}">{{ $old }}</textarea>
                                </div>
                                @break

                            @case('checkbox')
                                @php
                                    $options = json_decode($question->options) ?? [];
                                    $oldArray = is_array($old) ? $old : [];
                                @endphp
                                <div class="control">
                                    @foreach($options as $opt)
                                        <label class="checkbox mr-4">
                                            <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}"
                                                {{ in_array($opt, $oldArray) ? 'checked' : '' }}>
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('radio')
                                @php
                                    $options = json_decode($question->options) ?? [];
                                @endphp
                                <div class="control">
                                    @foreach($options as $opt)
                                        <label class="radio mr-4">
                                            <input type="radio" name="{{ $name }}" value="{{ $opt }}"
                                                {{ $old === $opt ? 'checked' : '' }}>
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>
                                @break
                        @endswitch

                        @error("answers.{$question->id}")
                            <p class="help text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="buttons">
                    <button type="submit" class="button blue">
                        <span class="icon"><i class="mdi mdi-send"></i></span>
                        <span>Kirim Laporan</span>
                    </button>
                    <a href="{{ url()->previous() }}" class="button red">
                        <span class="icon"><i class="mdi mdi-close"></i></span>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
