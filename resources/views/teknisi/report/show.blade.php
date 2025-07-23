@extends(auth()->user()->role === 'admin' ? 'admin.app' : 'teknisi.app')

@section('content')
<section class="is-title-bar py-3 border-bottom bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-4">
        <div>
            <h1 class="h3 mb-1 text-dark">Report Details</h1>
            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}. Report Informations</small>
        </div>
    </div>
</section>

<section class="section main-section text-dark">
    <div class="card px-4 py-4 shadow-sm border-0">
        <div class="mb-4">
            <h5 class="text-dark">Reporter Information</h5>
            <p><strong>Name:</strong> {{ $report->reporter->name }}</p>
            <p><strong>ID:</strong> {{ $report->reporter->npm }}</p>
            <p><strong>Telephone:</strong> {{ $report->reporter->telephone }}</p>
        </div>

        <div class="mb-4">
            <h5 class="text-dark">Computer Information</h5>
            <p><strong>PC Name:</strong> {{ $report->pc->pc_name ?? 'PC-' . $report->pc_id }}</p>
            <p><strong>Laboratory:</strong> {{ $report->pc->lab->lab_name ?? 'Lab-' . optional($report->pc)->lab_id }}</p>
        </div>

        <div class="mb-4">
            <h5 class="text-dark">Form Information</h5>
            <p><strong>Form Title:</strong> {{ $report->form->title }}</p>
        </div>

        <div class="mb-4">
            <h5 class="text-dark">Answers</h5>
            <ul class="list-group">
                @foreach ($report->answers as $answer)
                @php
                    $parsed = json_decode($answer->answer_text, true);
                @endphp

                <li class="list-group-item">
                    <strong>{{ $answer->question->question_text }}:</strong><br>
                    Nilai: <span>{{ $parsed['value'] ?? $answer->answer_text }}</span><br>
                    @if (!empty($parsed['note']))
                        Keterangan: <em>{{ $parsed['note'] }}</em>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            <a href="{{ route($role . '.report.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Back to Report List
            </a>
        </div>
    </div>
</section>
@endsection
