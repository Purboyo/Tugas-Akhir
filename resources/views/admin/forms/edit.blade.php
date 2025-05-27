@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="section main-section">
    <form action="{{ route($role.'.form.update', $form->id) }}" method="POST" id="form-builder">
        @csrf
        @method('PUT')
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">Edit Form</p>
            </header>

            <div class="card-content">
                <div class="field">
                    <label class="label">Form Title</label>
                    <div class="control">
                        <input type="text" name="title" class="input" value="{{ $form->title }}" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Lab</label>
                    <div class="control">
                        <select name="lab_id" class="input" required>
                            <option value="">-- Select Lab --</option>
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}" {{ $form->lab_id == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->lab_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <label class="label">Questions</label>
                <div id="questions-container" class="space-y-4"></div>

                <button type="button" class="button green is-small mt-2" onclick="addQuestion()">
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                    Add Question
                </button>
            </div>

            <footer class="card-footer">
                <button type="submit" class="card-footer-item button blue">Update</button>
                <a href="{{ route($role.'.form.index') }}" class="card-footer-item button red">Cancel</a>
            </footer>
        </div>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let questionCount = 0;

function addQuestion(question = {}) {
    const container = document.getElementById('questions-container');
    const qIndex = questionCount++;

    const optionsHtml = (question.options || []).map(opt => `
        <div class="field option-item flex items-center space-x-2 mb-2">
            <input type="text" name="questions[${qIndex}][options][]" class="input w-full" value="${opt}">
            <button type="button" class="button red is-small" onclick="this.parentElement.remove()">
                <span class="icon"><i class="mdi mdi-close"></i></span>
            </button>
        </div>
    `).join('');

    const questionEl = document.createElement('div');
    questionEl.className = 'box question-item p-3 bg-white shadow rounded relative';
    questionEl.innerHTML = `
        <span class="drag-handle absolute top-2 right-2 cursor-move"><i class="mdi mdi-drag"></i></span>
        <div class="field">
            <label class="label">Question</label>
            <input type="text" name="questions[${qIndex}][question_text]" class="input" required value="${question.question_text || ''}">
        </div>
        <div class="field">
            <label class="label">Type</label>
            <select name="questions[${qIndex}][type]" class="input type-select" onchange="toggleOptions(this, ${qIndex})" required>
                <option value="text" ${question.type === 'text' ? 'selected' : ''}>Text</option>
                <option value="number" ${question.type === 'number' ? 'selected' : ''}>Number</option>
                <option value="checkbox" ${question.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                <option value="radio" ${question.type === 'radio' ? 'selected' : ''}>Radio</option>
                <option value="textarea" ${question.type === 'textarea' ? 'selected' : ''}>Textarea</option>
            </select>
        </div>
        <div class="options-container mb-4" id="options-${qIndex}" style="display: ${['checkbox','radio'].includes(question.type) ? 'block' : 'none'};">
            <label class="label">Options</label>
            <div id="options-list-${qIndex}">
                ${optionsHtml}
            </div>
            <button type="button" class="button green is-small mt-2" onclick="addOption(${qIndex})">
                <span class="icon"><i class="mdi mdi-plus"></i></span> Add Option
            </button>
        </div>
        <div class="flex space-x-2 mt-3">
            <button type="button" class="button red is-small" onclick="this.closest('.question-item').remove()">
                <span class="icon"><i class="mdi mdi-delete"></i></span> Delete
            </button>
            <button type="button" class="button btn-secondary" onclick="duplicateQuestion(this)">Duplicate</button>
        </div>
    `;

    container.appendChild(questionEl);
    new Sortable(container, {
        animation: 150,
        handle: '.drag-handle',
    });
}

function toggleOptions(select, qIndex) {
    const type = select.value;
    const container = document.getElementById(`options-${qIndex}`);
    container.style.display = (type === 'checkbox' || type === 'radio') ? 'block' : 'none';
}

function addOption(qIndex) {
    const list = document.getElementById(`options-list-${qIndex}`);
    list.insertAdjacentHTML('beforeend', `
        <div class="field option-item flex items-center space-x-2 mb-2">
            <input type="text" name="questions[${qIndex}][options][]" class="input w-full" placeholder="Isi pilihan...">
            <button type="button" class="button red is-small" onclick="this.parentElement.remove()">
                <span class="icon"><i class="mdi mdi-close"></i></span>
            </button>
        </div>
    `);
}

function duplicateQuestion(button) {
    const original = button.closest('.question-item');
    const questionData = {
        question_text: original.querySelector('input[name^="questions"]')?.value,
        type: original.querySelector('select[name^="questions"]')?.value,
        options: Array.from(original.querySelectorAll('input[name*="[options]"]')).map(input => input.value)
    };
    addQuestion(questionData);
}

// Initialize questions from old data
document.addEventListener('DOMContentLoaded', () => {
    const existingQuestions = @json($form->questions ?? []);
    existingQuestions.forEach(q => addQuestion(q));
});
</script>
@endsection
