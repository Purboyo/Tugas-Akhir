@extends('public.app')

@section('content')
<section class="section main-section">
    <div class="card max-w-md mx-auto p-6 text-center">
        <h1 class="title is-3 mb-4">Terima kasih!</h1>
        <p class="mb-4">Laporan Anda telah berhasil dikirim.</p>
        <a href="{{ route('welcome', 1) }}" class="button is-primary">
            Kembali ke Halaman Utama
        </a>
    </div>
</section>
@endsection
