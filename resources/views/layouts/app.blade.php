<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/lisi_logo.png">
        <link rel="apple-touch-icon" href="/lisi_logo.png">

        <title>E-Intendance — {{ config('app.name', 'E-Intendance') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen bg-slate-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-transparent pt-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-slate-800">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Bannière changement mot de passe --}}
            @if(auth()->check() && auth()->user()->must_change_password)
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-amber-50 border border-amber-300 rounded-2xl overflow-hidden">
                        <div class="flex items-center space-x-3 bg-gradient-to-r from-amber-400 to-orange-400 px-5 py-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            <p class="text-white font-bold text-sm">Mot de passe temporaire</p>
                            <span class="text-amber-100 text-xs">— Vous utilisez un mot de passe réinitialisé par l'administrateur</span>
                        </div>

                        <div class="px-5 py-4">
                            @if(session('password_changed'))
                                <p class="text-green-700 font-semibold text-sm">✓ Mot de passe changé avec succès.</p>
                            @else
                                <p class="text-slate-600 text-sm mb-4">
                                    Définissez un nouveau mot de passe personnel. Cette notification disparaîtra après le changement.
                                    <span class="text-slate-400">(optionnel)</span>
                                </p>

                                @if($errors->has('new_password'))
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-2 text-xs mb-3">
                                        {{ $errors->first('new_password') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('emetteur.change-password') }}"
                                      class="flex flex-col sm:flex-row sm:items-end gap-3">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nouveau mot de passe</label>
                                        <input type="password" name="new_password" required minlength="8"
                                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white"
                                            placeholder="Minimum 8 caractères">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Confirmer</label>
                                        <input type="password" name="new_password_confirmation" required
                                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white"
                                            placeholder="Répéter le mot de passe">
                                    </div>
                                    <div class="flex items-center gap-3 pb-0.5">
                                        <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition shadow-sm whitespace-nowrap">
                                            Changer le mot de passe
                                        </button>
                                        <button type="button"
                                            onclick="this.closest('.max-w-7xl').querySelector('.bg-amber-50').style.display='none'"
                                            class="text-xs text-slate-400 hover:text-slate-600 transition font-medium whitespace-nowrap">
                                            Plus tard
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')

    </body>
</html>

