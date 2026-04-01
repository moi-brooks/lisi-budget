<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — Reliquat</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field { transition: all 0.15s ease; }
        .input-field:focus { outline: none; border-color: #4f46e5; background: #fff; box-shadow: 0 0 0 3px rgba(79,70,229,0.08); }
        .btn-primary { transition: all 0.2s ease; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(79,70,229,0.28); }
        .btn-primary:active { transform: translateY(0) scale(0.99); }
    </style>
</head>
<body class="antialiased min-h-screen bg-slate-100 flex items-center justify-center p-4">

    {{-- Modal-style card --}}
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl shadow-slate-900/10 overflow-hidden flex min-h-[580px]">

        {{-- LEFT PANEL — Navy --}}
        <div class="hidden md:flex md:w-[42%] bg-slate-900 flex-col justify-between p-10">
            {{-- Logo --}}
            <div>
                <img src="/logo.png" alt="Reliquat" class="h-8 w-auto">
            </div>

            {{-- Hero --}}
            <div class="space-y-4">
                <p class="text-indigo-400 text-xs font-semibold uppercase tracking-[0.2em]">Laboratoire LISI</p>
                <h1 class="text-white text-3xl font-bold leading-snug tracking-tight">
                    Bienvenue.<br>
                    <span class="text-slate-400 font-normal text-2xl">Gérez votre<br>budget de recherche.</span>
                </h1>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Système de gestion budgétaire pour les laboratoires de recherche universitaire.
                </p>
            </div>

            {{-- Footer --}}
            <p class="text-slate-600 text-xs">© {{ date('Y') }} Laboratoire LISI</p>
        </div>

        {{-- RIGHT PANEL — White --}}
        <div class="flex-1 flex items-center justify-center p-10">
            <div class="w-full max-w-sm">

                {{-- Mobile logo --}}
                <div class="mb-8 md:hidden">
                    <img src="/logo.png" alt="Reliquat" class="h-8 w-auto">
                </div>

                {{-- Heading --}}
                <div class="mb-7">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Connexion</h2>
                    <p class="text-slate-400 text-sm mt-1">Accédez à votre espace de gestion.</p>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-800 placeholder-slate-300"
                                placeholder="nom@uiz.ac.ma">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="text-xs" />
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] text-indigo-500 hover:text-indigo-700 font-semibold transition">Oublié ?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-800 placeholder-slate-300"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="text-xs" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-slate-200 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                        <label for="remember_me" class="ml-2.5 text-xs font-medium text-slate-400 cursor-pointer">Rester connecté</label>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="btn-primary w-full flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold py-3.5 rounded-xl text-sm">
                            Se connecter
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-xs text-slate-300">© {{ date('Y') }} Reliquat — Laboratoire LISI</p>
            </div>
        </div>

    </div>

</body>
</html>
