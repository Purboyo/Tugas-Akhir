@extends('teknisi.app')

@section('content')

<form method="POST" action="{{ route('teknisi.report.submitBadReport') }}">
    @csrf
@foreach($pcs as $group)
    @php
        $firstReport = $group->first(); // ambil satu report dari grup
        $pc = $firstReport->pc;         // akses relasi pc
    @endphp

    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $pc->pc_name }}</h5>
            <input type="hidden" name="descriptions[{{ $pc->id }}]" value="PC rusak."> 
            <textarea name="descriptions[{{ $pc->id }}]" class="form-control" placeholder="Keterangan kerusakan"></textarea>
        </div>
    </div>
@endforeach


    <button type="submit" class="btn btn-primary">Kirim Laporan ke Kepala Lab</button>
</form>

@endsection

