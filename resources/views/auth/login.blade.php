<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — LISI Budget</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Manrope', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="antialiased selection:bg-primary selection:text-white overflow-hidden">
    {{-- Dynamic Background --}}
    <div class="fixed inset-0 z-[-1] bg-[#0f172a] overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-900/30 rounded-full blur-[120px]"></div>
        <div class="absolute top-[20%] right-[10%] w-[30%] h-[30%] bg-blue-900/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-[1100px] grid grid-cols-1 lg:grid-cols-2 bg-white/5 rounded-[64px] border border-white/10 shadow-2xl overflow-hidden backdrop-blur-sm">
            
            {{-- Left Side: Branding/Visual --}}
            <div class="hidden lg:flex flex-col justify-between p-16 relative bg-gradient-to-br from-primary via-indigo-950 to-slate-950 text-white overflow-hidden">
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-12">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-xl">
                            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-2xl font-display font-black tracking-tighter uppercase italic">LISI<span class="text-white/50">Budget</span></span>
                    </div>

                    <h1 class="text-5xl font-display font-black leading-[1.1] mb-8 tracking-tighter">
                        La Performance <br>
                        <span class="text-indigo-400 font-black italic">Académique</span> <br>
                        par le Budget.
                    </h1>
                    <p class="text-indigo-200/70 text-lg leading-relaxed max-w-sm font-medium">
                        Système de gestion budgétaire intelligent pour les laboratoires de recherche de l'Université.
                    </p>
                </div>

                <div class="relative z-10 flex items-center space-x-6">
                    <div class="flex -space-x-3">
                        @for($i=1; $i<=3; $i++)
                            <div class="w-10 h-10 rounded-full border-2 border-white/20 bg-slate-800"></div>
                        @endfor
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-indigo-300/60">+12 Chercheurs Actifs</span>
                </div>
            </div>

            {{-- Right Side: Login Form --}}
            <div class="p-12 lg:p-20 bg-white/95 backdrop-blur-xl flex flex-col justify-center">
                <div class="max-w-md mx-auto w-full space-y-10">
                    <div>
                        <h2 class="text-4xl font-display font-black text-slate-900 tracking-tight mb-2">Bon retour.</h2>
                        <p class="text-slate-500 font-medium tracking-tight italic">Accédez à votre console de gestion budgétaire.</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-8">
                        @csrf

                        <!-- Email Address -->
                        <div class="space-y-2 group">
                            <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Adresse Email PRO</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" /></svg>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                                    class="w-full bg-slate-50 border-slate-100 border-2 rounded-2xl pl-12 pr-6 py-4 text-slate-700 font-bold focus:bg-white focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                    placeholder="nom@uiz.ac.ma">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-2 group">
                            <div class="flex items-center justify-between ml-1">
                                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest group-focus-within:text-primary transition-colors italic">Mot de passe</label>
                                @if (Route::has('password.request'))
                                    <a class="text-[10px] font-bold text-slate-400 hover:text-primary transition-colors uppercase tracking-widest" href="{{ route('password.request') }}">
                                        Oublié ?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" /></svg>
                                </span>
                                <input id="password" type="password" name="password" required 
                                    class="w-full bg-slate-50 border-slate-100 border-2 rounded-2xl pl-12 pr-6 py-4 text-slate-700 font-bold focus:bg-white focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-slate-200 text-primary shadow-sm focus:ring-primary focus:ring-offset-2 transition-all cursor-pointer" name="remember">
                                <span class="ms-3 text-sm font-bold text-slate-500 group-hover:text-slate-900 transition-colors uppercase tracking-widest text-[10px]">Rester connecté</span>
                            </label>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                class="w-full bg-gradient-to-r from-primary to-indigo-700 hover:scale-[1.02] active:scale-[0.98] text-white font-bold py-5 rounded-[24px] shadow-2xl shadow-primary/30 transition-all duration-300 flex items-center justify-center space-x-3">
                                <span>Se Connecter</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center pt-8 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] italic">
                            © {{ date('Y') }} Laboratoire LISI — UI V2.0
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
