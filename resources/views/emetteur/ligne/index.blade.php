<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Propositions Budgétaires') }}
            </h2>
            <a href="{{ route('emetteur.lignes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
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

            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('emetteur.lignes.index', ['statut' => 'en_attente']) }}" class="{{ $status === 'en_attente' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        En attente
                    </a>
                    <a href="{{ route('emetteur.lignes.index', ['statut' => 'approuve']) }}" class="{{ $status === 'approuve' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Approuvées
                    </a>
                    <a href="{{ route('emetteur.lignes.index', ['statut' => 'rejete']) }}" class="{{ $status === 'rejete' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Rejetées
                    </a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-xs text-gray-600 border-b">
                            <th class="p-4">Date</th>
                            <th class="p-4">Ligne Budgétaire</th>
                            <th class="p-4">Montant (DH)</th>
                            <th class="p-4">Motif Réponse</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propositions as $prop)
                            <tr class="border-b hover:bg-gray-50 text-sm">
                                <td class="p-4">{{ $prop->created_at->format('d/m/Y') }}</td>
                                <td class="p-4">
                                    <div class="font-mono text-xs text-gray-500">{{ $prop->ligne->code_complet }}</div>
                                    <div class="font-semibold">{{ $prop->ligne->nom }}</div>
                                </td>
                                <td class="p-4 font-bold text-blue-600">{{ number_format($prop->montant, 2, ',', ' ') }}</td>
                                
                                <td class="p-4 text-gray-600">
                                    @if($status === 'rejete')
                                        <div class="text-red-600 font-bold text-xs mb-1">Motif du rejet de l'administration :</div>
                                        <div class="italic border-l-2 border-red-500 pl-2 text-red-800 mb-2">{{ $prop->motif_refus }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                
                                <td class="p-4 text-center border-l">
                                    @if($status === 'en_attente' || $status === 'rejete')
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('emetteur.lignes.edit', $prop) }}" class="text-yellow-600 hover:text-yellow-900 border border-yellow-600 px-2 py-1 rounded text-xs font-bold">Modifier</a>
                                            <form action="{{ route('emetteur.lignes.destroy', $prop) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de la proposition ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 border border-red-600 px-2 py-1 rounded text-xs font-bold">Annuler</button>
                                            </form>
                                        </div>
                                    @elseif($status === 'approuve')
                                        <a href="{{ route('emetteur.engagements.create', ['ligne_id' => $prop->id]) }}" class="bg-purple-100 text-purple-700 hover:bg-purple-200 px-3 py-1 rounded text-xs font-bold inline-block">Créer un BC</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Vous n'avez aucune proposition {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</x-app-layout>
