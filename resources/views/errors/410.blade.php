@extends('layouts.app')

@section('title', 'Produk Sudah Tidak Tersedia')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="text-center">
        <p class="text-6xl font-extrabold text-primary-500 mb-4">410</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-3">Produk Sudah Tidak Tersedia</h1>
        <p class="text-slate-500 text-sm max-w-md mx-auto mb-8">
            Maaf, produk yang Anda cari sudah tidak kami jual lagi dan telah dihapus secara permanen dari katalog. Silakan jelajahi koleksi kami yang lain.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all shadow-sm">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>Lihat Koleksi Lain</span>
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-6 py-3 rounded-xl text-sm transition-all">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</section>
@endsection
