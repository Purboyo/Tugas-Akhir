@extends('public.app')

@section('content')

<section class="section main-section text-dark">
    <div class="card mx-auto shadow-sm" style="max-width: 600px;">
        <div class="card-body">
            <h1 class="card-title text-center mb-4">Isi Form Laporan untuk <span class="text-primary">{{ $pc->lab->lab_name }}</span></h1>

            <div class="mb-3 text-center">
                <p>
                    Pastikan Anda mengisi semua informasi yang diperlukan dengan jujur dan lengkap. 
                    Ini akan membantu tim teknisi kami untuk menangani laporan Anda dengan cepat dan efisien.
                    Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi staf lab.
                </p>
            </div>

            <form action="{{ route('form.submit', $form) }}" method="POST" class="space-y-6">
                @csrf

                {{-- Optional: Hidden PC ID --}}
                <input type="hidden" name="pc_id" value="{{ $pc->id }}">

                <div class="form-group">
                    <label class="font-semibold mb-1 text-gray-700">Nama</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-account-outline text-sm"></i></span>
                        </div>
                        <input type="text" name="reporter[name]" value="{{ old('reporter.name') }}" required class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('reporter.name')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="font-semibold mb-1 text-gray-700">NPM/NID/ID/Lainya</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="mdi mdi-card-account-details text-sm"></i></span>
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
                            <span class="input-group-text"><i class="mdi mdi-phone-outline text-sm"></i></span>
                        </div>
                        <input type="text" name="reporter[telephone]" value="{{ old('reporter.telephone') }}" class="form-control border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('reporter.telephone')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="mt-3">

                @foreach($form->questions as $question)
                    <div class="form-group text-dark">
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
        @php $options = json_decode($question->options, true); @endphp
            <div class="d-flex flex-wrap gap-2">
                @foreach ($options as $option)
                    <div class="form-check">
                        <input 
                            type="radio" 
                            name="answers[{{ $question->id }}][value]" 
                            value="{{ $option }}" 
                            class="form-check-input skala-radio" 
                            data-max="{{ end($options) }}"
                            id="q{{ $question->id }}_{{ $loop->index }}"
                        >
                        <label class="form-check-label" for="q{{ $question->id }}_{{ $loop->index }}">{{ $option }}</label>
                    </div>
                @endforeach
            </div>
            {{-- Input keterangan --}}
            <div class="mt-2">
                <textarea 
                    name="answers[{{ $question->id }}][note]" 
                    class="form-control mt-2 q-keterangan" 
                    placeholder="Berikan keterangan jika nilai kurang dari maksimal (opsional)"
                    style="display: none;"
                ></textarea>
            </div>
                                @break
                        @endswitch

                        @error("answers.{$question->id}")
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="d-flex justify-content-between pt-4">
                    <button type="submit" class="btn btn-outline-primary px-6 py-2">
                        <i class="mdi mdi-send mr-1"></i> Kirim Laporan
                    </button>
                    <a href="{{ route('welcome', ['id' => $pc->id]) }}" class="btn btn-outline-danger px-6 py-2">
                        <i class="mdi mdi-close mr-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.skala-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            const groupName = this.name;
            const radios = document.querySelectorAll(`input[name="${groupName}"]`);
            let selected = this.value;
            let max = this.dataset.max;

            // Temukan textarea keterangan
            const wrapper = this.closest('.form-group');
            const keteranganBox = wrapper.querySelector('.q-keterangan');

            if (selected !== max) {
                keteranganBox.style.display = 'block';
            } else {
                keteranganBox.style.display = 'none';
                keteranganBox.value = '';
            }
        });
    });
});
</script>

@endsection

