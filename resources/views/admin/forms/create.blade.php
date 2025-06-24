@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Form Builder</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Add Form</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <div class="flex items-center">
                <h2 class="text-gray-800 text-l font-semibold ml-2">Add Form</h2>
            </div>
        </header>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Builder</h4>
            </div>
            <div class="card-body text-dark">
                <form action="{{ route($role.'.form.store') }}" method="POST" id="form-builder">
                    @csrf

                    <div class="form-group">
                        <label>Form Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="Enter form title">
                    </div>

                    <div class="form-group">
                        <label for="lab_id">Pilih Laboratorium</label>
                        <select class="form-control multi-select" name="lab_id[]" multiple="multiple" required>
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->lab_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="form-group text-dark">
                        <label>Questions</label>
                        <div id="questions-container" class="space-y-4"></div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addQuestion()">
                            <i class="mdi mdi-plus"></i> Add Question
                        </button>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-info" onclick="previewForm()">
                            <i class="mdi mdi-eye"></i> Preview
                        </button>
                        <div>
                            <a href="{{ route($role.'.form.index') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="mdi mdi-content-save"></i> Save Form
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let questionCount = 0;

function addQuestion(question = {}) {
    const container = document.getElementById('questions-container');
    const qIndex = questionCount++;

    const optionsHtml = (question.options || []).map(opt => `
        <div class="input-group mb-2 option-item">
            <input type="text" name="questions[${qIndex}][options][]" class="form-control" value="${opt}">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove()">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    `).join('');

    const questionEl = document.createElement('div');
    questionEl.className = 'p-3 border rounded bg-light position-relative question-item';
    questionEl.innerHTML = `
        <div class="position-absolute end-0 top-0 m-2 drag-handle cursor-move"><i class="mdi mdi-drag"></i></div>
        <div class="form-group">
            <label>Question</label>
            <input type="text" name="questions[${qIndex}][question_text]" class="form-control" required value="${question.question_text || ''}">
        </div>
        <div class="form-group">
            <label>Type of Question</label>
            <select name="questions[${qIndex}][type]" class="form-control type-select" onchange="toggleOptions(this, ${qIndex})" required>
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="checkbox">Checkbox</option>
                <option value="radio">Radio</option>
                <option value="textarea">Textarea</option>
            </select>
        </div>
        <div class="form-group" id="options-${qIndex}" style="display: none;">
            <label>Answer Options</label>
            <div id="options-list-${qIndex}">
                ${optionsHtml}
            </div>
            <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addOption(${qIndex})">
                <i class="mdi mdi-plus"></i> Add Option
            </button>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.question-item').remove()">
                <i class="mdi mdi-delete"></i> Delete
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="duplicateQuestion(this)">
                Duplicate
            </button>
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
        <div class="input-group mb-2 option-item">
            <input type="text" name="questions[${qIndex}][options][]" class="form-control" placeholder="Isi pilihan...">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove()">
                <i class="mdi mdi-close"></i>
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

<script>
function previewForm() {
    const title = document.querySelector('input[name="title"]').value;
    const labSelect = document.querySelector('select[name="lab_id"]');
    const labName = labSelect.options[labSelect.selectedIndex].text;

    const questionItems = document.querySelectorAll('.question-item');
    let questionsHtml = '';

    if (questionItems.length === 0) {
        questionsHtml = '<p class="text-muted">no questions yet.</p>';
    } else {
        questionsHtml += '<ol class="text-start">';
        questionItems.forEach((item, index) => {
            const qText = item.querySelector('input[name*="[question_text]"]').value;
            const qType = item.querySelector('select[name*="[type]"]').value;
            const optionList = item.querySelectorAll('.option-item input');
            let optionsHtml = '';

            if (optionList.length > 0) {
                optionsHtml = '<ul>';
                optionList.forEach(opt => {
                    optionsHtml += `<li>${opt.value}</li>`;
                });
                optionsHtml += '</ul>';
            }

            questionsHtml += `<li><strong>${qText}</strong><br><em>Type: ${qType}</em>${optionsHtml}</li>`;
        });
        questionsHtml += '</ol>';
    }

    Swal.fire({
        title: 'Preview Form',
        html: `
            <div class="text-start">
                <p><strong>title:</strong> ${title || '-'}<br>
                <strong>Laboratory:</strong> ${labName || '-'}</p>
                <hr>
                <h5>Questions:</h5>
                ${questionsHtml}
            </div>
        `,
        width: '700px',
        confirmButtonText: 'Close',
        customClass: {
            htmlContainer: 'text-start'
        }
    });
}
</script>
@endsection
