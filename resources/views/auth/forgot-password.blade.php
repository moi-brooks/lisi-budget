<x-guest-layout>
    <div class="mb-4 text-sm text-slate-600 text-center">
        <svg class="w-10 h-10 text-indigo-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
        <p class="font-semibold text-slate-800 mb-2">Mot de passe oublié ?</p>
        <p class="text-slate-500 leading-relaxed">
            Contactez votre administrateur pour réinitialiser votre mot de passe.
        </p>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
            ← Retour à la connexion
        </a>
    </div>
</x-guest-layout>
