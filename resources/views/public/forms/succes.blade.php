@extends('public.app')

@section('content')
<section class="section main-section text-dark">
    <div class="card mx-auto shadow-sm" style="max-width: 600px;">
        <div class="card-body text-center text-dark">
            <h1 class="card-title text-center mb-4">
                <span class="text-primary">Terima Kasih!</span>
            </h1>
            
            <div class="mb-4">
                <i class="mdi mdi-check-circle-outline text-success" style="font-size: 5rem;"></i>
            </div>
            
            <p class="lead mb-4">
                Laporan Anda telah berhasil dikirim dan akan segera diproses.
            </p>
            
            <div class="alert alert-info mb-4" role="alert">
                <i class="mdi mdi-information-outline mr-2"></i>
                Tim teknisi akan segera menindaklanjuti laporan Anda dalam waktu 1x24 jam.
            </div>
            
            <div class="border-top pt-3 mb-4">
                <p class="text-muted small mb-0">
                    <i class="mdi mdi-clock-outline mr-1"></i>
                    Tanggal Pengiriman: {{ now()->format('d F Y H:i') }}
                </p>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('welcome', $pc->id) }}" class="btn btn-primary btn-lg">
                    <i class="mdi mdi-home mr-2"></i> Kembali ke Halaman Utama
                </a>
            </div>
            <div class="mt-4">
                <p class="text-muted small">
                    Note: Jika Anda ingin mengirimkan kembali laporan, silahkan klik tombol "Kembali ke Halaman Utama"
                </p>
            </div>
        </div>
    </div>
</section>
@endsection