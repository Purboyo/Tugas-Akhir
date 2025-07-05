@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')

<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Form Builder</h1>
            <small class="text-muted">{{ ucfirst(Auth::user()->role) }} · Edit Form</small>
        </div>
    </div>
</section>

<section class="section main-section py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <header class="bg-gray-200 p-4">
            <h2 class="text-gray-800 text-l font-semibold">Edit Form</h2>
        </header>
        <div class="card">
            <div class="card-body text-dark">
                <form action="{{ route($role.'.form.update', $form->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Form Title</label>
                        <input type="text" name="title" class="form-control" required value="{{ $form->title }}">
                    </div>

                    <div class="form-group">
                        <label>Select Laboratory</label>
                        <select class="form-control multi-select text-dark" name="lab_id[]" multiple="multiple" required>
                            @foreach ($labs as $lab)
                                <option class="text-dark" value="{{ $lab->id }}" {{ in_array($lab->id, $form->laboratories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $lab->lab_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->role === 'teknisi' && $defaultQuestions->isNotEmpty())
                        <hr class="my-4">
                        <div class="form-group">
                            <h5 class="text-dark mb-3">📌 Default Questions by Admin</h5>

                            @foreach($defaultQuestions as $dq)
                                @php
                                    $options = is_string($dq->options) ? json_decode($dq->options, true) : $dq->options;
                                @endphp

                                <div class="p-3 mb-3 border rounded bg-light shadow-sm">
                                    <label class="form-label mb-1 fw-bold d-block">
                                        {{ $dq->question_text }}
                                        <span class="badge bg-secondary text-white">{{ ucfirst($dq->type) }}</span>
                                    </label>

                                    @if(in_array($dq->type, ['radio', 'checkbox']) && is_array($options))
                                        <ul class="mb-0 ps-3">
                                            @foreach($options as $opt)
                                                <li>{{ $opt }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <hr class="my-4">
                    <div class="form-group text-dark">
                        <label>Technician Questions</label>
                        <div id="questions-container" class="space-y-4">
                            {{-- Akan diisi dengan JavaScript dari existingQuestions --}}
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addQuestion()">
                            <i class="mdi mdi-plus"></i> Add Question
                        </button>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route($role.'.form.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="mdi mdi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="mdi mdi-content-save"></i> Save Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@php
    $existingQuestions = $customQuestions->map(function($q) {
        return [
            'question_text' => $q->question_text,
            'type' => $q->type,
            'options' => is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []),
        ];
    })->values()->toArray();
@endphp

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let questionCount = 0;
    const existingQuestions = @json($existingQuestions);

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

        const selectedType = question.type || 'text';

        const questionEl = document.createElement('div');
        questionEl.className = 'p-3 border rounded bg-light position-relative question-item';
        questionEl.innerHTML = `
            <div class="position-absolute end-0 top-0 m-2 drag-handle cursor-move"><i class="mdi mdi-drag"></i></div>
            <div class="form-group">
                <label>Questions</label>
                <input type="text" name="questions[${qIndex}][question_text]" class="form-control" required value="${question.question_text || ''}">
            </div>
            <div class="form-group">
                <label>Answer Type</label>
                <select name="questions[${qIndex}][type]" class="form-control type-select" onchange="toggleOptions(this, ${qIndex})" required>
                    <option value="text" ${selectedType === 'text' ? 'selected' : ''}>Text</option>
                    <option value="number" ${selectedType === 'number' ? 'selected' : ''}>Number</option>
                    <option value="checkbox" ${selectedType === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                    <option value="radio" ${selectedType === 'radio' ? 'selected' : ''}>Radio</option>
                    <option value="textarea" ${selectedType === 'textarea' ? 'selected' : ''}>Textarea</option>
                </select>
            </div>
            <div class="form-group" id="options-${qIndex}" style="display: ${(selectedType === 'checkbox' || selectedType === 'radio') ? 'block' : 'none'};">
                <label>Options</label>
                <div id="options-list-${qIndex}">${optionsHtml}</div>
                <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addOption(${qIndex})">
                    <i class="mdi mdi-plus"></i> Add Option
                </button>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.question-item').remove()">
                    <i class="mdi mdi-delete"></i> Delete
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="duplicateQuestion(this)">Duplicate</button>
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
                <input type="text" name="questions[${qIndex}][options][]" class="form-control" placeholder="Enter option...">
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
        const newIndex = questionCount++;

        clone.querySelectorAll('input, select').forEach(el => {
            if(el.name) {
                el.name = el.name.replace(/questions\[\d+\]/, `questions[${newIndex}]`);
                if(el.tagName === 'INPUT' && el.type === 'text') el.value = el.value || '';
            }
        });

        clone.querySelector('[id^="options-"]').id = `options-${newIndex}`;
        clone.querySelector('[id^="options-list-"]').id = `options-list-${newIndex}`;
        clone.querySelector('button.btn-success').setAttribute('onclick', `addOption(${newIndex})`);

        container.appendChild(clone);
    }

    window.onload = function() {
        if(existingQuestions.length > 0) {
            existingQuestions.forEach(q => addQuestion(q));
        } else {
            addQuestion();
        }
    };
</script>
@endsection
