<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Gestion des Émetteurs') }}
            </h2>
            <a href="{{ route('admin.emetteurs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300 shadow-sm">
                Nouvel Émetteur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('new_password'))
                <div class="mb-6 bg-amber-50 border-2 border-amber-400 text-amber-900 px-5 py-4 rounded-xl relative">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        <div>
                            <p class="font-bold text-amber-800 mb-1">Mot de passe réinitialisé pour <span class="underline">{{ session('new_password_user') }}</span></p>
                            <p class="text-sm mb-2">Communiquez ce mot de passe à l'utilisateur. Il ne sera plus affiché après cette page.</p>
                            <div class="inline-flex items-center bg-white border border-amber-300 rounded-lg px-4 py-2 space-x-3">
                                <span class="font-mono font-bold text-lg tracking-widest text-slate-800">{{ session('new_password') }}</span>
                                <span class="text-xs text-amber-600 font-semibold uppercase">Nouveau mot de passe</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                            <th class="p-4">Nom Complet</th>
                            <th class="p-4">Profession</th>
                            <th class="p-4">Budget (Saison)</th>
                            <th class="p-4">Dotation (DH)</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emetteurs as $emetteur)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                <td class="p-4 font-semibold text-slate-800">
                                    {{ $emetteur->user->name }}
                                    <div class="text-xs text-slate-400 font-normal mt-0.5">{{ $emetteur->user->email }}</div>
                                </td>
                                <td class="p-4 text-slate-600">{{ $emetteur->profession ?? '-' }}</td>
                                <td class="p-4 text-slate-600">{{ $emetteur->budget->saison }}</td>
                                <td class="p-4 font-bold text-indigo-600">{{ number_format($emetteur->dotation, 2, ',', ' ') }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.emetteurs.edit', $emetteur) }}" class="inline-flex items-center justify-center bg-amber-50 text-amber-700 hover:bg-amber-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Modifier</a>
                                        <form action="{{ route('admin.emetteurs.reset-password', $emetteur) }}" method="POST" onsubmit="return confirm('Réinitialiser le mot de passe de {{ $emetteur->user->name }} ?');" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                                MDP
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.emetteurs.destroy', $emetteur) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500 font-medium">Aucun émetteur configuré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
