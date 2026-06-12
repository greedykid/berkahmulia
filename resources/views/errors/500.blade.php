@extends('layouts.app')

@section('title', 'Terjadi Kesalahan')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="text-center">
        <p class="text-6xl font-extrabold text-rose-500 mb-4">500</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-3">Terjadi Kesalahan Server</h1>
        <p class="text-slate-500 text-sm max-w-md mx-auto mb-8">
            Maaf, terjadi kesalahan pada server kami. Tim kami sudah diberitahu dan sedang memperbaikinya. Silakan coba lagi nanti.
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all shadow-sm">
            <i class="fa-solid fa-house text-xs"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>
</section>
@endsection
