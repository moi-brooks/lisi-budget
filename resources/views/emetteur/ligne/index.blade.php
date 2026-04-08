<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Mes Propositions Budgétaires') }}
            </h2>
            <a href="{{ route('emetteur.lignes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition duration-300 shadow-sm text-sm inline-flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Proposition
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
            
            @if(auth()->user()->emetteur->is_reliquat_faible)
                <div class="mb-4 bg-rose-50 border border-rose-400 text-rose-700 px-4 py-3 rounded-xl relative shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <strong>Alerte :</strong> &nbsp;Votre reliquat est inférieur à 10% de votre dotation.
                </div>
            @endif

            <!-- Tabs -->
            <div class="mb-8">
                <nav class="flex space-x-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 max-w-fit">
                    <a href="{{ route('emetteur.lignes.index', ['statut' => 'en_attente']) }}" class="{{ $status === 'en_attente' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        En attente
                    </a>
                    <a href="{{ route('emetteur.lignes.index', ['statut' => 'approuve']) }}" class="{{ $status === 'approuve' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Approuvées
                    </a>
                    <a href="{{ route('emetteur.lignes.index', ['statut' => 'rejete']) }}" class="{{ $status === 'rejete' ? 'bg-rose-50 text-rose-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Rejetées
                    </a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                            <th class="p-4">Date</th>
                            <th class="p-4">Ligne Budgétaire</th>
                            <th class="p-4">Montant (DH)</th>
                            <th class="p-4">Motif Réponse</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propositions as $prop)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                <td class="p-4 text-slate-600">{{ $prop->created_at->format('d/m/Y') }}</td>
                                <td class="p-4">
                                    <div class="font-mono text-xs text-slate-400 mb-1 border border-slate-200 inline-block px-1.5 py-0.5 rounded bg-slate-50">{{ $prop->ligne->code_complet }}</div>
                                    <div class="font-semibold text-slate-700">{{ $prop->ligne->nom }}</div>
                                </td>
                                <td class="p-4 font-bold text-indigo-600">{{ number_format($prop->montant, 2, ',', ' ') }}</td>
                                
                                <td class="p-4 text-slate-600">
                                    @if($status === 'rejete')
                                        <div class="text-rose-600 font-bold text-xs mb-1">Motif du rejet :</div>
                                        <div class="italic border-l-2 border-rose-400 pl-2 text-rose-800 mb-2 text-xs leading-relaxed">{{ $prop->motif_refus }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                
                                <td class="p-4 text-center border-l border-slate-50">
                                    @if($status === 'en_attente' || $status === 'rejete')
                                        <div class="flex justify-center flex-col space-y-2">
                                            <a href="{{ route('emetteur.lignes.edit', $prop) }}" class="inline-flex items-center justify-center bg-amber-50 text-amber-700 hover:bg-amber-100 transition rounded-lg px-3 py-1.5 font-semibold text-xs">Modifier</a>
                                            <form action="{{ route('emetteur.lignes.destroy', $prop) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de la proposition ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full inline-flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 transition rounded-lg px-3 py-1.5 font-semibold text-xs">Annuler</button>
                                            </form>
                                        </div>
                                    @elseif($status === 'approuve')
                                        <a href="{{ route('emetteur.engagements.create', ['ligne_id' => $prop->id]) }}" class="inline-flex items-center justify-center bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition rounded-lg px-3 py-1.5 font-semibold text-xs">Créer un BC</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500 font-medium">Vous n'avez aucune proposition {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
