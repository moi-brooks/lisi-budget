<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — Reliquat</title>
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
<body class="antialiased min-h-screen bg-[#f1f5f9] flex flex-col items-center justify-center px-4 py-8 gap-6">

    {{-- College logo — outside, above card --}}
    <div class="flex flex-col items-center gap-2">
        <img src="/logo_fssm_transparent.png" alt="Faculté des Sciences Semlalia — Université Cadi Ayyad" class="h-24 w-auto drop-shadow-sm">
    </div>

    {{-- Modal card --}}
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl shadow-slate-900/8 overflow-hidden flex" style="min-height: 520px;">

        {{-- LEFT — Navy panel --}}
        <div class="hidden md:flex md:w-[42%] relative flex-col justify-between p-10 overflow-hidden" style="background: #0f172a;">

            {{-- Service branding inside modal --}}
            <div class="flex items-center gap-2.5">
                <svg class="h-7 w-7 text-white" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="6" y="6" width="3.5" height="28" rx="1" fill="currentColor"/>
                    <rect x="6" y="6" width="18" height="3.5" rx="1" fill="currentColor"/>
                    <rect x="20.5" y="6" width="3.5" height="3.5" rx="1" fill="currentColor"/>
                    <rect x="21.5" y="8" width="3.5" height="10" rx="1" fill="currentColor"/>
                    <rect x="6" y="18" width="17" height="3.5" rx="1" fill="currentColor"/>
                    <rect x="20" y="16" width="3.5" height="5.5" rx="1" fill="currentColor"/>
                    <rect x="13" y="28" width="3.5" height="6" rx="1" fill="currentColor"/>
                    <rect x="19" y="24" width="3.5" height="10" rx="1" fill="currentColor"/>
                    <rect x="25" y="20" width="3.5" height="14" rx="1" fill="currentColor"/>
                </svg>
                <span class="font-bold text-white text-lg tracking-tight">Reliquat</span>
            </div>

            <div class="space-y-3">
                <h1 class="text-white text-3xl font-bold leading-tight tracking-tight">
                    Bienvenue.
                </h1>
                <p class="text-slate-400 text-base font-normal leading-snug">
                    Gérez votre budget<br>de recherche.
                </p>
                <p class="text-slate-600 text-xs leading-relaxed max-w-[220px] pt-1">
                    Système de gestion budgétaire pour les laboratoires de recherche universitaire.
                </p>
            </div>

            <p class="text-slate-700 text-[10px] font-semibold uppercase tracking-widest">LISI — Université Cadi Ayyad</p>
        </div>

        {{-- RIGHT — Form --}}
        <div class="flex-1 flex items-center justify-center px-10 py-12">
            <div class="w-full max-w-xs">

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Connexion</h2>
                    <p class="text-slate-400 text-sm mt-1">Accédez à votre espace de gestion.</p>
                </div>

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
                                class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-300"
                                placeholder="exemple@uca.ac.ma">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="text-xs" />
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Mot de passe</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] text-indigo-500 hover:text-indigo-700 font-semibold transition-colors">Oublié ?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-300"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="text-xs" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-slate-200 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                        <label for="remember_me" class="ml-2.5 text-xs text-slate-400 cursor-pointer">Rester connecté</label>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-1">
                        <button type="submit"
                            class="btn-submit w-full flex items-center justify-center gap-2 text-white font-semibold py-3 rounded-xl text-sm"
                            style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                            Se connecter
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-[11px] text-slate-300">
                    © {{ date('Y') }} LISI — Université Cadi Ayyad
                </p>
            </div>
        </div>

    </div>

</body>
</html>
