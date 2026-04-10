<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — E-Intendance</title>
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field { transition: border-color 0.15s, box-shadow 0.15s, background 0.15s; }
        .input-field:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .btn-submit { transition: transform 0.15s, box-shadow 0.15s; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(99,102,241,0.35); }
        .btn-submit:active { transform: translateY(0) scale(0.99); }
        .fssm-watermark { filter: invert(1) brightness(2); opacity: 0.06; pointer-events: none; }
    </style>
</head>
<body class="antialiased min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6" style="background: radial-gradient(circle at top right, #f8fafc, #eff6ff, #f1f5f9);">

    <div class="w-full max-w-[480px]">
        
        {{-- Branding Top --}}
        <div class="text-center mb-12 transform-gpu transition-all duration-700 delay-100" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
            <div class="inline-flex items-center justify-center p-6 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 mb-8 border border-slate-100 relative group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <img src="/lisi_logo_transparent.png" alt="LISI" class="h-20 w-auto drop-shadow-xl relative z-10 transition-transform duration-500 group-hover:scale-105">
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tightest">E-Intendance</h1>
            <p class="text-slate-500 text-sm mt-3 font-semibold uppercase tracking-widest opacity-80">Laboratoire d'Informatique et des Systèmes d'Intelligence</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200 border border-slate-100/50 p-8 md:p-10">
            
            <div class="mb-8 text-center">
                <h2 class="text-lg font-bold text-slate-800">Accès sécurisé</h2>
                <div class="h-1 w-12 bg-indigo-500 mx-auto mt-2 rounded-full opacity-20"></div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest pl-1">Identifiant</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input-field w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3.5 text-[15px] text-slate-800 placeholder-slate-400"
                            placeholder="exemple@uca.ac.ma">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="text-xs" />
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between pl-1">
                        <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Mot de passe</label>
                        <a href="{{ route('password.request') }}" class="text-[11px] text-slate-400 hover:text-indigo-600 font-bold transition-colors">Perdu ?</a>
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="input-field w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3.5 text-[15px] text-slate-800 placeholder-slate-400"
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Remember & Security --}}
                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded-md border-slate-200 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 transition-all">
                        <span class="ml-2.5 text-xs text-slate-500 group-hover:text-slate-700 transition-colors">Rester connecté</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                        class="btn-submit w-full flex items-center justify-center gap-3 text-white font-bold py-4 rounded-2xl text-[15px] shadow-lg shadow-indigo-200"
                        style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                        Se connecter
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

        </div>

        {{-- Institutional Footer --}}
        <div class="mt-12 text-center opacity-60 flex flex-col items-center gap-4">
            <img src="/logo_fssm_transparent.png" alt="FSSM" class="h-12 w-auto grayscale contrast-125 brightness-50">
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-bold">
                Faculté des Sciences Semlalia — Marrakech
            </p>
        </div>

    </div>

</body>
</html>
