@extends('public.app')

@section('content')
<section class="section main-section text-dark">
    <div class="card mx-auto shadow-sm" style="max-width: 600px;">
        <div class="card-body">
            <h1 class="card-title text-center mb-4">Selamat Datang di <span class="text-primary">{{ $pc->lab->lab_name }}</span></h1>

            <div class="mb-3 text-center">
                <p class="lead">
                    Berikut adalah detail PC yang Anda pilih.
                </p>
                <p>
                    Formulir yang tersedia melalui QR Code di bawah ini berfungsi untuk melakukan <strong>pengecekan fungsionalitas komputer</strong> secara lengkap.<br>
                    Dengan melakukan pengecekan ini, Anda dapat membantu memastikan bahwa PC selalu dalam kondisi optimal dan siap digunakan.
                </p>
                <p>
                    Pastikan untuk mengisi formulir dengan jujur dan lengkap agar tim teknisi dapat segera menangani jika ada masalah.
                </p>
            </div>

            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>Nama PC:</strong> {{ $pc->pc_name }}
                </li>
                <li class="list-group-item">
                    <strong>Penanggung Jawab:</strong> 
                    @if($pc->lab->technician)
                        {{ $pc->lab->technician->name }}
                    @else
                        <span class="text-warning">Belum Ditentukan</span>
                    @endif
                </li>
                <li class="list-group-item">
                    <strong>Lab:</strong> {{ $pc->lab->lab_name }}
                </li>
            </ul>

            <div class="alert alert-info text-center" role="alert">
                <i class="mdi mdi-qrcode-scan mdi-24px align-middle"></i>  
                Silakan <strong>scan QR Code</strong> di bawah ini menggunakan ponsel Anda untuk mengakses formulir pengecekan fungsionalitas PC ini.
            </div>

            <div class="text-center mb-4">
                <div class="d-inline-block p-3 border rounded">
            <img src="{{ asset('storage/' . $pc->qr_code) }}" alt="QR Code" width="220">
                </div>
            </div>

            <div class="text-center">
                <a href="{{ $formUrl }}" class="btn btn-success btn-lg" role="button" aria-label="Isi Form Sekarang">
                    <i class="mdi mdi-arrow-right-bold-circle-outline me-2"></i> Isi Form Sekarang
                </a>
            </div>

            <hr class="mt-5">

            <p class="text-muted text-center small">
                Apabila Anda mengalami kesulitan dalam pengisian formulir atau menemukan masalah pada PC, silakan hubungi staf lab untuk mendapatkan bantuan lebih lanjut.
            </p>
        </div>
    </div>
</section>
@endsection