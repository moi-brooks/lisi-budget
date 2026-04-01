<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Détail Bon de Commande') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 mb-8 p-6 lg:p-8 flex flex-col md:flex-row justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2 text-slate-800">{{ $engagement->commentaire ?? 'Aucun commentaire' }}</h3>
                    <p class="text-slate-500 mb-6 text-sm">Émis par : <strong class="text-slate-700">{{ $engagement->emetteur?->user?->name ?? 'Inconnu' }}</strong> le {{ $engagement->created_at?->format('d/m/Y') ?? 'N/A' }}</p>
                    
                    <div class="mb-4">
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">Fournisseur</span>
                        <span class="font-medium text-slate-700 text-lg">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
                    </div>
                    
                    <div>
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">Imputé sur la ligne</span>
                        <div class="inline-flex items-center">
                            <span class="font-mono text-xs bg-slate-50 border border-slate-200 text-slate-500 px-2 py-1 rounded-md">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                            <span class="text-sm font-medium text-slate-700 ml-3">{{ $engagement->ligneProposee?->ligne?->nom ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 md:mt-0 md:text-right flex flex-col justify-between">
                    <div>
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-2">Statut Actuel</span>
                        <x-status-badge :status="$engagement->statut" class="text-sm px-3 py-1" />
                        @if($engagement->statut === 'rejete')
                            <p class="text-rose-600 font-medium text-sm mt-3 text-left md:text-right max-w-xs">{{ $engagement->motif_refus }}</p>
                        @endif
                    </div>
                    
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">TVA Globale</span>
                        <span class="text-lg font-bold text-slate-700">{{ $engagement->tva }} %</span>
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mt-4 mb-1">Montant Total HT</span>
                        <span class="text-xl font-bold text-slate-800">{{ number_format($engagement->total_ht, 2, ',', ' ') }} DH</span>
                        <span class="text-xs uppercase tracking-wider text-indigo-400 font-semibold block mt-4 mb-1">Montant Total TTC Estimé</span>
                        <span class="text-4xl font-extrabold text-indigo-600 tracking-tight">{{ number_format($engagement->total_ttc, 2, ',', ' ') }} <small class="text-lg text-indigo-400 font-semibold">DH</small></span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Articles & Besoins liés</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 font-semibold border-b border-slate-100">
                                <th class="p-4">Intitulé de l'article</th>
                                <th class="p-4 text-center">Qté</th>
                                <th class="p-4 text-right">PU HT (DH)</th>
                                <th class="p-4 text-right">Total HT (DH)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($engagement->besoins as $besoin)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-800">{{ $besoin->intitule }}</div>
                                        @if($besoin->description)
                                            <div class="text-xs text-slate-500 mt-1">{{ $besoin->description }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center text-slate-700">{{ $besoin->quantite }}</td>
                                    <td class="p-4 text-right text-slate-600">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}</td>
                                    <td class="p-4 text-right font-bold text-indigo-600">{{ number_format($besoin->montant, 2, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 font-medium italic">Aucun article dans ce bon de commande.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($engagement->statut === 'en_attente')
                <div class="bg-white shadow-sm rounded-2xl border border-indigo-100 mb-8 p-6 lg:p-8 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-y-0 left-0 w-1 bg-indigo-500"></div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg mb-1">Décision d'administration</h4>
                        <p class="text-sm text-slate-500">Veuillez examiner ce bon de commande avant de l'approuver ou de le rejeter.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 mt-6 md:mt-0 w-full md:w-auto">
                        <form action="{{ route('admin.engagements.approve', $engagement->id) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-6 rounded-xl transition duration-300 shadow-sm" onclick="return confirm('Confirmer l\'approbation de ce BC ?');">
                                Approuver le BC
                            </button>
                        </form>
                        
                        <button @click="$dispatch('open-modal-reject', { id: '{{ $engagement->id }}' })" class="w-full sm:w-auto bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold py-2.5 px-6 rounded-xl transition duration-300">
                            Rejeter
                        </button>
                    </div>
                </div>

                <x-modal-reject :id="$engagement->id" :route="route('admin.engagements.reject', $engagement->id)" title="Rejeter le bon de commande" />
            @endif

            <div class="mt-8">
                <a href="{{ route('admin.engagements.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
