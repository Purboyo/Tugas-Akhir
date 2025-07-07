@extends('jurusan.app')

@section('title', 'Dashboard Jurusan')

@section('content')
<div class="container-fluid py-4 text-dark">
    <h2 class="mb-4">Dashboard Jurusan</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h5 class="card-title">Laporan Send</h5>
                    <h3 class="text-info">{{ $totalSend }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Laboratorium</h5>
                    <h3 class="text-primary">{{ $totalLab }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
