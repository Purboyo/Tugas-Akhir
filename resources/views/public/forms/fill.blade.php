@extends('public.app')

@section('content')

<section class="py-6 border-bottom mb-6">
    <div class="container mx-auto px-4">
        <nav class="text-sm text-gray-500 mb-2">
            <ol class="list-reset flex">
                <li><a href="#" class="text-blue-600 hover:underline">User </a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="#" class="text-blue-600 hover:underline">Form Laporan</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-700">Isi Form</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800">{{ $form->title }}</h1>
    </div>
</section>

<section class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">

        <form action="{{ route('form.submit', $form) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Optional: Hidden PC ID --}}
            <input type="hidden" name="pc_id" value="{{ $pc->id }}">

            <div class="form-group">
                <label class="font-semibold mb-1 text-gray-700">Nama</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-account text-xl"></i></span>
                    </div>
                    <input type="text" name="reporter[name]" value="{{ old('reporter.name') }}" required class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                @error('reporter.name')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-semibold mb-1 text-gray-700">NPM/NIP/ID</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-card-account-details text-xl"></i></span>
                    </div>
                    <input type="text" name="reporter[npm]" value="{{ old('reporter.npm') }}" required class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                @error('reporter.npm')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="font-semibold mb-1 text-gray-700">Telephone/Wa</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-phone text-xl"></i></span>
                    </div>
                    <input type="text" name="reporter[telephone]" value="{{ old('reporter.telephone') }}" class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                @error('reporter.telephone')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @foreach($form->questions as $question)
                <div class="form-group">
                    <label class="font-semibold mb-1 text-gray-700">{{ $question->question_text }}</label>
                    @php
                        $name = "answers[{$question->id}]";
                        $old = old("answers.{$question->id}");
                    @endphp

                    @switch($question->type)
                        @case('text')
                            <input type="text" name="{{ $name }}" value="{{ $old }}" class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @break

                        @case('number')
                            <input type="number" name="{{ $name }}" value="{{ $old }}" class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @break

                        @case('textarea')
                            <textarea name="{{ $name }}" rows="4" class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ $old }}</textarea>
                            @break

                        @case('checkbox')
                            @php $options = json_decode($question->options) ?? []; $oldArray = is_array($old) ? $old : []; @endphp
                            <div class="form-check">
                                @foreach($options as $opt)
                                    <div class="form-check">
                                        <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}" {{ in_array($opt, $oldArray) ? 'checked' : '' }} class="form-check-input">
                                        <label class="form-check-label">{{ $opt }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @break

                        @case('radio')
                            @php $options = json_decode($question->options) ?? []; @endphp
                            <div class="form-check">
                                @foreach($options as $opt)
                                    <div class="form-check">
                                        <input type="radio" name="{{ $name }}" value="{{ $opt }}" {{ $old === $opt ? 'checked' : '' }} class="form-check-input">
                                        <label class="form-check-label">{{ $opt }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @break
                    @endswitch

                    @error("answers.{$question->id}")
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="d-flex justify-content-between pt-4">
                <button type="submit" class="btn btn-primary px-6 py-2">
                    <i class="mdi mdi-send mr-1"></i> Kirim Laporan
                </button>
                <a href="{{ route('welcome', ['id' => $pc->id]) }}" class="btn btn-danger px-6 py-2">
                    <i class="mdi mdi-close mr-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</section>

@endsection