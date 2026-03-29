<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bons de Commande (Engagements)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.engagements.index', ['statut' => 'en_attente']) }}" class="{{ $status === 'en_attente' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        En attente
                    </a>
                    <a href="{{ route('admin.engagements.index', ['statut' => 'approuve']) }}" class="{{ $status === 'approuve' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Approuvés
                    </a>
                    <a href="{{ route('admin.engagements.index', ['statut' => 'rejete']) }}" class="{{ $status === 'rejete' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Rejetés
                    </a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-xs text-gray-600 border-b">
                            <th class="p-4">Date</th>
                            <th class="p-4">Émetteur</th>
                            <th class="p-4">Objet</th>
                            <th class="p-4">Montant Total TTC (DH)</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engagements as $engagement)
                            <tr class="border-b hover:bg-gray-50 text-sm">
                                <td class="p-4">{{ $engagement->created_at->format('d/m/Y') }}</td>
                                <td class="p-4 font-semibold">{{ $engagement->emetteur->user->name }}</td>
                                <td class="p-4">
                                    <div class="font-bold">{{ $engagement->objet }}</div>
                                    <div class="text-xs text-gray-500">{{ $engagement->fournisseur?->nom ?? 'Fournisseur non défini' }}</div>
                                </td>
                                <td class="p-4 font-bold text-blue-600">{{ number_format($engagement->montant_total, 2, ',', ' ') }}</td>
                                
                                <td class="p-4 text-center">
                                    <a href="{{ route('admin.engagements.show', $engagement) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded text-xs font-bold inline-block">Voir détails</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Aucun bon de commande {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</x-app-layout>
