@extends('teknisi.app')

@section('content')

<a href="{{ route('teknisi.report.index') }}" class="btn btn-outline-secondary mb-3">Kembali
</a>

<form method="POST" action="{{ route('teknisi.report.submitBadReport') }}">
    @csrf

    @foreach($pcs as $group)
        @php
            $firstReport = $group->first();
            $pc = $firstReport->pc;
            $badAnswers = $firstReport->answers->filter(function($answer) use ($burukKeywords) {
                foreach ($burukKeywords as $keyword) {
                    if (stripos($answer->answer_text, $keyword) !== false) {
                        return true;
                    }
                }
                return false;
            });
        @endphp

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3 font-weight-bold text-primary">{{ $pc->pc_name }}</h5>

                {{-- Tampilkan jawaban penyebab status "Bad" --}}
                @if ($badAnswers->count())
                    <ul class="list-group mb-3">
                        @foreach ($badAnswers as $answer)
                            @php
                                $parsed = json_decode($answer->answer_text, true);
                            @endphp
                            <li class="list-group-item">
                                <strong>{{ $answer->question->question_text }}:</strong><br>
                                @if (is_array($parsed) && isset($parsed['value']))
                                    <span>Nilai: {{ $parsed['value'] }}</span><br>
                                    @if (!empty($parsed['note']))
                                        <span>Catatan: {{ $parsed['note'] }}</span>
                                    @endif
                                @else
                                    {{ $answer->answer_text }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada jawaban buruk terdeteksi.</p>
                @endif

                {{-- Textarea untuk catatan tambahan --}}
                <textarea name="descriptions[{{ $pc->id }}]" class="form-control" rows="3" placeholder="Keterangan tambahan kerusakan (opsional)..."></textarea>
            </div>
        </div>
    @endforeach

    <div class="text-right">
        <button type="submit" class="btn btn-outline-primary">Submit Report to Lab Head
        </button>
    </div>
</form>

@endsection
