@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="section main-section">
    <form action="{{ route($role.'.form.store') }}" method="POST" id="form-builder">
        @csrf
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">Create New Form</p>
            </header>

            <div class="card-content">
                <div class="field">
                    <label class="label">Form Title</label>
                    <div class="control">
                        <input type="text" name="title" class="input" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Lab</label>
                    <div class="control">
                        <select name="lab_id" class="input">
                            <option value="">-- Select Lab --</option>
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->lab_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>

                <label class="label">Questions</label>
                <div id="questions-container" class="space-y-4"></div>

                <button type="button" class="button green is-small mt-2" onclick="addQuestion()">
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                    Add Question
                </button>
            </div>

            <footer class="card-footer">
                <button type="button" class="card-footer-item button is-info" onclick="previewForm()">Preview</button>
                <button type="submit" class="card-footer-item button blue">Save</button>
                <a href="{{ route($role.'.form.index') }}" class="card-footer-item button">Cancel</a>
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
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="checkbox">Checkbox</option>
                <option value="radio">Radio</option>
                <option value="textarea">Textarea</option>
            </select>
        </div>
        <div class="options-container mb-4" id="options-${qIndex}" style="display: none;">
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
            <button type="button" class="button is-small" onclick="duplicateQuestion(this)">Duplicate</button>
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
    const clone = original.cloneNode(true);
    const container = document.getElementById('questions-container');
    container.appendChild(clone);
}
</script>


@endsection
