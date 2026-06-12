@extends('layouts.admin')

@section('title', 'Ubah Password')
@section('page_title', 'Ubah Password')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Ubah Password</h2>
    <p class="text-sm text-slate-500 mt-1">Ganti password akun admin Anda.</p>
</div>

@if ($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-start gap-2 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5"></i>
        <div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('admin.settings.updatePassword') }}" method="POST">
    @csrf
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm max-w-lg">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                <i class="fa-solid fa-lock text-rose-600"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Ganti Password</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Masukkan password lama dan password baru.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Password Lama</label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
                    <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
            <div>
                <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required minlength="8"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
                    <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Minimal 8 karakter.</p>
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-5 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Ubah Password</span>
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
