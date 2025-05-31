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
            <div class="flex items-center">
                <span class="text-gray-600 text-xl mdi mdi-file-edit-outline"></span>
                <h2 class="text-gray-800 text-l font-semibold ml-2">Edit Form</h2>
            </div>
        </header>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Builder</h4>
            </div>
            <div class="card-body">
                <form action="{{ route($role.'.form.update', $form->id) }}" method="POST" id="form-builder">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Judul Form</label>
                        <input type="text" name="title" class="form-control" required placeholder="Masukkan judul form" value="{{ old('title', $form->title) }}">
                    </div>

                    <div class="form-group">
                        <label>Pilih Laboratorium</label>
                        <select name="lab_id" class="form-control" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('lab_id', $form->lab_id) == $lab->id ? 'selected' : '' }}>{{ $lab->lab_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <div id="questions-container" class="space-y-4"></div>
                        <button type="button" class="btn btn-success btn-sm mt-2" onclick="addQuestion()">
                            <i class="mdi mdi-plus"></i> Tambah Pertanyaan
                        </button>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-info" onclick="previewForm()">
                            <i class="mdi mdi-eye"></i> Preview
                        </button>
                        <div>
                            <a href="{{ route($role.'.form.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Update Form
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@php
$existingQuestions = old('questions', $form->questions->map(function($q) {
    return [
        'question_text' => $q->question_text,
        'type' => $q->type,
        'options' => $q->options ? json_decode($q->options) : [],
    ];
})->toArray());
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
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">
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
                <label>Pertanyaan</label>
                <input type="text" name="questions[${qIndex}][question_text]" class="form-control" required value="${question.question_text || ''}">
            </div>
            <div class="form-group">
                <label>Jenis Jawaban</label>
                <select name="questions[${qIndex}][type]" class="form-control type-select" onchange="toggleOptions(this, ${qIndex})" required>
                    <option value="text" ${selectedType === 'text' ? 'selected' : ''}>Text</option>
                    <option value="number" ${selectedType === 'number' ? 'selected' : ''}>Number</option>
                    <option value="checkbox" ${selectedType === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                    <option value="radio" ${selectedType === 'radio' ? 'selected' : ''}>Radio</option>
                    <option value="textarea" ${selectedType === 'textarea' ? 'selected' : ''}>Textarea</option>
                </select>
            </div>
            <div class="form-group" id="options-${qIndex}" style="display: ${(selectedType === 'checkbox' || selectedType === 'radio') ? 'block' : 'none'};">
                <label>Opsi Jawaban</label>
                <div id="options-list-${qIndex}">
                    ${optionsHtml}
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addOption(${qIndex})">
                    <i class="mdi mdi-plus"></i> Tambah Opsi
                </button>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.question-item').remove()">
                    <i class="mdi mdi-delete"></i> Hapus
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="duplicateQuestion(this)">
                    Duplikat
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
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
        `);
    }
    
    function duplicateQuestion(button) {
        const original = button.closest('.question-item');
        const clone = original.cloneNode(true);
    
        // Update question index on clone so name attribute tidak bentrok
        const container = document.getElementById('questions-container');
        const newIndex = questionCount++;
        
        // Perbarui semua input dan select name attribute di clone
        clone.querySelectorAll('input, select').forEach(el => {
            if(el.name) {
                el.name = el.name.replace(/questions\[\d+\]/, `questions[${newIndex}]`);
                if(el.tagName === 'INPUT' && el.type === 'text') el.value = el.value || '';
            }
        });
    
        // Perbarui id div options agar unik juga
        const oldId = clone.querySelector('[id^="options-"]').id;
        const newOptionsId = `options-${newIndex}`;
        clone.querySelector('[id^="options-"]').id = newOptionsId;
    
        const oldOptionsListId = clone.querySelector('[id^="options-list-"]').id;
        const newOptionsListId = `options-list-${newIndex}`;
        clone.querySelector('[id^="options-list-"]').id = newOptionsListId;
    
        // Perbarui tombol tambah opsi onclick
        const btnAddOption = clone.querySelector('button.btn-success');
        btnAddOption.setAttribute('onclick', `addOption(${newIndex})`);
    
        container.appendChild(clone);
    }
    
    // Inisialisasi pertanyaan saat halaman load
    window.onload = function() {
        if(existingQuestions.length > 0) {
            existingQuestions.forEach(q => addQuestion(q));
        } else {
            addQuestion();
        }
    };
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function previewForm() {
        const title = document.querySelector('input[name="title"]').value;
        const labSelect = document.querySelector('select[name="lab_id"]');
        const labName = labSelect.options[labSelect.selectedIndex].text;
    
        const questionItems = document.querySelectorAll('.question-item');
        let questionsHtml = '';
    
        if (questionItems.length === 0) {
            questionsHtml = '<p class="text-muted">Belum ada pertanyaan ditambahkan.</p>';
        } else {
            questionItems.forEach((item, index) => {
                const qText = item.querySelector('input[name*="[question_text]"]').value;
                const qType = item.querySelector('select[name*="[type]"]').value;
                const optionList = item.querySelectorAll('.option-item input');
    
                let optionsHtml = '';
                if (optionList.length > 0) {
                    optionsHtml = '<ul class="mb-0 ps-4">';
                    optionList.forEach(opt => {
                        optionsHtml += `<li class="text-sm">${opt.value}</li>`;
                    });
                    optionsHtml += '</ul>';
                }
    
                questionsHtml += `
                    <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                        <p class="mb-1"><strong>Pertanyaan ${index + 1}:</strong> ${qText}</p>
                        <p class="mb-2"><span class="badge bg-info text-dark">Jenis: ${qType}</span></p>
                        ${optionsHtml}
                    </div>
                `;
            });
        }
    
        Swal.fire({
            title: 'Preview Form',
            html: `
                <div class="text-start align-items-center">
                    <div class="mb-3">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-nowrap">Judul Form</th>
                                    <td>: ${title || '-'}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">Laboratorium</th>
                                    <td>: ${labName || '-'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h5 class="mb-3">Pertanyaan:</h5>
                    ${questionsHtml}
                </div>
            `,
            width: '750px',
            confirmButtonText: 'Tutup',
            customClass: {
                htmlContainer: 'text-start'
            }
        });
    }

</script>

@endsection
