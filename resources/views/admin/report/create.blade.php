@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="section main-section">
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">Isi Laporan Pemeriksaan</p>
        </header>
        <div class="card-content space-y-4">
            @if(session('success'))
                <div class="alert alert-success p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            <form action="{{ route('reports.store') }}" method="POST" id="reportForm" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-semibold">Nama</label>
                        <input type="text" name="name" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">NPM / NIP</label>
                        <input type="text" name="npm" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">Komputer</label>
                        <select name="computer_id" class="input input-bordered w-full" required>
                            <option value="">-- Pilih Komputer --</option>
                            @foreach ($computers as $pc)
                                <option value="{{ $pc->id }}">{{ $pc->computer_name ?? 'PC-'.$pc->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">Form</label>
                        <select name="form_id" class="input input-bordered w-full" required onchange="loadQuestions(this)">
                            <option value="">-- Pilih Form --</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form->id }}" data-questions='@json($form->questions)'>
                                    {{ $form->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <div id="questions-area" class="space-y-4"></div>

                <button type="submit" class="button blue w-full">Submit Laporan</button>
            </form>
        </div>
    </div>
</section>

<script>
function loadQuestions(select) {
    const selectedOption = select.options[select.selectedIndex];
    const questions = JSON.parse(selectedOption.dataset.questions || '[]');
    const container = document.getElementById('questions-area');
    container.innerHTML = '';

    questions.forEach(question => {
        let inputHtml = '';

        switch (question.type) {
            case 'text':
            case 'number':
                inputHtml = `<input type="${question.type}" name="answers[${question.id}]" class="input input-bordered w-full" required>`;
                break;
            case 'textarea':
                inputHtml = `<textarea name="answers[${question.id}]" class="input input-bordered w-full" rows="3" required></textarea>`;
                break;
            case 'radio':
            case 'checkbox':
                const type = question.type;
                inputHtml = question.options.map(opt => `
                    <label class="inline-flex items-center mr-4">
                        <input type="${type}" name="answers[${question.id}]${type === 'checkbox' ? '[]' : ''}" value="${opt}" class="mr-2">
                        ${opt}
                    </label>
                `).join('');
                break;
        }

        container.innerHTML += `
            <div class="question-block">
                <label class="font-semibold block mb-2">${question.question_text}</label>
                ${inputHtml}
            </div>
        `;
    });
}
</script>
@endsection
