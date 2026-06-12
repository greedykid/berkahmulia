@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="text-center">
        <p class="text-6xl font-extrabold text-primary-500 mb-4">404</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-slate-500 text-sm max-w-md mx-auto mb-8">
            Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan. Silakan kembali ke beranda atau cari produk yang Anda inginkan.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all shadow-sm">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Kembali ke Beranda</span>
            </a>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-6 py-3 rounded-xl text-sm transition-all">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>Cari Produk</span>
            </a>
        </div>
    </div>
</section>
@endsection
