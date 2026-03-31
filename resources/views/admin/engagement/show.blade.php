<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détail Bon de Commande') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6 flex flex-col md:flex-row justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-1">{{ $engagement->commentaire ?? 'Aucun commentaire' }}</h3>
                    <p class="text-gray-600 mb-4">Émis par : <strong>{{ $engagement->emetteur?->user?->name ?? 'Inconnu' }}</strong> le {{ $engagement->created_at?->format('d/m/Y') ?? 'N/A' }}</p>
                    
                    <div class="mb-2">
                        <span class="text-sm text-gray-500 block">Fournisseur</span>
                        <span class="font-semibold">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-500 block">Imputé sur la ligne</span>
                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                        <span class="text-sm ml-2">{{ $engagement->ligneProposee?->ligne?->nom ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0 md:text-right flex flex-col justify-between">
                    <div>
                        <span class="text-sm text-gray-500 block">Statut Actuel</span>
                        <x-status-badge :status="$engagement->statut" class="text-sm px-3 py-1" />
                        @if($engagement->statut === 'rejete')
                            <p class="text-red-600 text-xs mt-2 text-left md:text-right max-w-xs">{{ $engagement->motif_refus }}</p>
                        @endif
                    </div>
                    
                    <div class="mt-6 border-t pt-4">
                        <span class="text-sm text-gray-500 block">TVA Globale</span>
                        <span class="text-lg font-bold text-gray-700">{{ $engagement->tva }} %</span>
                        <span class="text-sm text-gray-500 block mt-2">Montant Total HT</span>
                        <span class="text-xl font-bold text-gray-800">{{ number_format($engagement->total_ht, 2, ',', ' ') }} DH</span>
                        <span class="text-sm text-gray-500 block mt-2">Montant Total TTC</span>
                        <span class="text-3xl font-bold text-blue-600">{{ number_format($engagement->total_ttc, 2, ',', ' ') }} <small class="text-sm text-gray-500">DH</small></span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-0 overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white text-sm">
                            <th class="p-3">Intitulé de l'article</th>
                            <th class="p-3 text-center">Qté</th>
                            <th class="p-3 text-right">Prix Unitaire (DH)</th>
                            <th class="p-3 text-right">Total HT (DH)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engagement->besoins as $besoin)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="font-semibold">{{ $besoin->intitule }}</div>
                                    @if($besoin->description)
                                        <div class="text-xs text-gray-500">{{ $besoin->description }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-center">{{ $besoin->quantite }}</td>
                                <td class="p-3 text-right">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}</td>
                                <td class="p-3 text-right font-bold">{{ number_format($besoin->montant, 2, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center text-gray-500">Aucun article dans ce bon de commande.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($engagement->statut === 'en_attente')
                <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6 flex flex-col md:flex-row items-center justify-between border-l-4 border-indigo-500">
                    <div>
                        <h4 class="font-bold text-gray-800">Décision d'administration</h4>
                        <p class="text-sm text-gray-600">Veuillez examiner ce bon de commande avant de l'approuver ou de le rejeter.</p>
                    </div>
                    
                    <div class="flex space-x-3 mt-4 md:mt-0">
                        <form action="{{ route('admin.engagements.approve', $engagement->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow" onclick="return confirm('Confirmer l\'approbation de ce BC ?');">
                                Approuver le BC
                            </button>
                        </form>
                        
                        <button @click="$dispatch('open-modal-reject', { id: '{{ $engagement->id }}' })" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow">
                            Rejeter
                        </button>
                    </div>
                </div>

                <x-modal-reject :id="$engagement->id" :route="route('admin.engagements.reject', $engagement->id)" title="Rejeter le bon de commande" />
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.engagements.index') }}" class="text-blue-600 hover:underline">&larr; Retour à la liste</a>
            </div>
        </div>
    </div>
</x-app-layout>
