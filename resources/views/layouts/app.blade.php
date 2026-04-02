<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="apple-touch-icon" href="/favicon.png">

        <title>Reliquat — {{ config('app.name', 'Reliquat') }}</title>

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

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')

        @if(auth()->check() && auth()->user()->must_change_password)
        {{-- Bannière persistante changement de mot de passe --}}
        <div id="change-password-banner" class="fixed inset-0 z-50 flex items-start justify-center pt-8 px-4" style="background: rgba(15,23,42,0.5);">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="bg-gradient-to-r from-amber-400 to-orange-400 px-6 py-4 flex items-center space-x-3">
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <div>
                        <p class="text-white font-bold text-sm">Mot de passe temporaire</p>
                        <p class="text-amber-100 text-xs">Vous utilisez un mot de passe réinitialisé par l'administrateur</p>
                    </div>
                </div>

                <div class="px-6 py-5">
                    @if(session('password_changed'))
                        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                            Mot de passe changé avec succès.
                        </div>
                    @else
                        <p class="text-slate-600 text-sm mb-4">
                            Nous vous recommandons de définir un nouveau mot de passe personnel. Cette notification disparaîtra après le changement.
                            <span class="text-slate-400">(optionnel)</span>
                        </p>

                        @if($errors->has('new_password'))
                            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-2 text-xs mb-3">
                                {{ $errors->first('new_password') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('emetteur.change-password') }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nouveau mot de passe</label>
                                <input type="password" name="new_password" required minlength="8"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 bg-slate-50"
                                    placeholder="Minimum 8 caractères">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Confirmer le mot de passe</label>
                                <input type="password" name="new_password_confirmation" required
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 bg-slate-50"
                                    placeholder="Répéter le mot de passe">
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <button type="button" onclick="document.getElementById('change-password-banner').style.display='none'"
                                    class="text-xs text-slate-400 hover:text-slate-600 transition font-medium">
                                    Plus tard
                                </button>
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-xl text-sm transition shadow-sm">
                                    Changer le mot de passe
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </body>
</html>

