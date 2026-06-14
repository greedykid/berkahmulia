<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Berkah Mulia</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Compiled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4 font-sans">

    <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-lg animate-fade-in">
        <!-- Brand Header -->
        <div class="text-center mb-8 flex flex-col items-center">
            <img src="{{ asset('logo.webp') }}" alt="Berkah Mulia Logo" class="h-16 w-16 rounded-2xl shadow-md border border-slate-100 object-cover mb-4">
            <span class="text-3xl font-extrabold bg-linear-to-r from-pink-500 to-indigo-500 bg-clip-text text-transparent">
                Berkah Mulia
            </span>
            <p class="text-slate-400 text-xs mt-1 font-semibold uppercase tracking-wider">Dashboard Administration</p>
        </div>

        <!-- Flash alerts -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-250 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <p class="text-xs font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-250 text-rose-800 px-4 py-3 rounded-xl flex items-start gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>
                <div class="text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="admin@bmberkahmulia.com"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-700 pl-10 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-sm transition-all">
                    <div class="absolute left-3.5 top-3.5 text-slate-400">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-700 pl-10 pr-11 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-sm transition-all">
                    <div class="absolute left-3.5 top-3.5 text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <button type="button" onclick="toggleLoginPassword()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1">
                        <i class="fa-solid fa-eye text-sm" id="login-eye-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox"
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-350 rounded">
                <label for="remember" class="ml-2 block text-xs font-semibold text-slate-500">
                    Ingat Saya di Perangkat Ini
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-all shadow-md text-sm tracking-wide">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-600">
                <i class="fa-solid fa-arrow-left mr-1"></i>Kembali ke Halaman Toko
            </a>
        </div>
    </div>

<script>
function toggleLoginPassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('login-eye-icon');
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

// Form loading state (prevent double-click and show loader)
const loginForm = document.querySelector('form');
if (loginForm) {
    loginForm.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Masuk ke Dashboard...';
        }
    });
}
</script>
</body>
</html>
