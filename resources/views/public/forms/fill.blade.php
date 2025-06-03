@extends('public.app')

@section('content')

<section class="py-6 border-b mb-6">
    <div class="container mx-auto px-4">
        <nav class="text-sm text-gray-500 mb-2">
            <ol class="list-reset flex">
                <li><a href="#" class="text-blue-600 hover:underline">User</a></li>
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
    <div class="max-w-3xl mx-auto bg-gray-200 rounded-2xl shadow p-6">

        <form action="{{ route('form.submit', $form) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Optional: Hidden PC ID --}}
            <input type="hidden" name="pc_id" value="{{ request()->query('pc') }}">

            <div>
                <label class="block font-semibold mb-1 text-gray-700">Nama</label>
                <div class="relative">
                    <input type="text" name="reporter[name]" value="{{ old('reporter.name') }}" required class="input w-full pl-10 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="mdi mdi-account text-xl"></i>
                    </div>
                </div>
                @error('reporter.name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-semibold mb-1 text-gray-700">NPM/NIP/ID</label>
                <div class="relative">
                    <input type="text" name="reporter[npm]" value="{{ old('reporter.npm') }}" required class="input w-full pl-10 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="mdi mdi-card-account-details text-xl"></i>
                    </div>
                </div>
                @error('reporter.npm')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @foreach($form->questions as $question)
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">{{ $question->question_text }}</label>
                    @php
                        $name = "answers[{$question->id}]";
                        $old = old("answers.{$question->id}");
                    @endphp

                    @switch($question->type)
                        @case('text')
                            <input type="text" name="{{ $name }}" value="{{ $old }}" class="input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @break

                        @case('number')
                            <input type="number" name="{{ $name }}" value="{{ $old }}" class="input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @break

                        @case('textarea')
                            <textarea name="{{ $name }}" rows="4" class="textarea w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ $old }}</textarea>
                            @break

                        @case('checkbox')
                            @php $options = json_decode($question->options) ?? []; $oldArray = is_array($old) ? $old : []; @endphp
                            <div class="flex flex-wrap gap-4">
                                @foreach($options as $opt)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}" {{ in_array($opt, $oldArray) ? 'checked' : '' }} class="form-checkbox text-blue-600">
                                        <span class="ml-2">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('radio')
                            @php $options = json_decode($question->options) ?? []; @endphp
                            <div class="flex flex-wrap gap-4">
                                @foreach($options as $opt)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="{{ $name }}" value="{{ $opt }}" {{ $old === $opt ? 'checked' : '' }} class="form-radio text-blue-600">
                                        <span class="ml-2">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break
                    @endswitch

                    @error("answers.{$question->id}")
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex items-center justify-between pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="mdi mdi-send mr-1"></i> Kirim Laporan
                </button>
                <a href="{{ route('welcome', ['id' => $pc->id]) }}" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
                    <i class="mdi mdi-close mr-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</section>

@endsection
