<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Propositions de Budget') }}
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
                    <a href="{{ route('admin.propositions.index', ['statut' => 'en_attente']) }}" class="{{ $status === 'en_attente' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        En attente
                    </a>
                    <a href="{{ route('admin.propositions.index', ['statut' => 'approuve']) }}" class="{{ $status === 'approuve' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Approuvées
                    </a>
                    <a href="{{ route('admin.propositions.index', ['statut' => 'rejete']) }}" class="{{ $status === 'rejete' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Rejetées
                    </a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-xs text-gray-600 border-b">
                            <th class="p-4">Date</th>
                            <th class="p-4">Émetteur</th>
                            <th class="p-4">Ligne Budgétaire</th>
                            <th class="p-4">Montant (DH)</th>
                            <th class="p-4">Justification</th>
                            @if($status === 'en_attente')
                                <th class="p-4 text-center">Actions</th>
                            @elseif($status === 'rejete')
                                <th class="p-4">Motif de rejet</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propositions as $prop)
                            <tr class="border-b hover:bg-gray-50 text-sm">
                                <td class="p-4">{{ $prop->created_at->format('d/m/Y') }}</td>
                                <td class="p-4 font-semibold">{{ $prop->emetteur->user->name }}</td>
                                <td class="p-4">
                                    <div class="font-mono text-xs text-gray-500">{{ $prop->ligne->code_complet }}</div>
                                    <div>{{ $prop->ligne->nom }}</div>
                                </td>
                                <td class="p-4 font-bold text-blue-600">{{ number_format($prop->montant, 2, ',', ' ') }}</td>
                                <td class="p-4 text-gray-600">{{ Str::limit($prop->justification, 50) ?? '-' }}</td>
                                
                                @if($status === 'en_attente')
                                    <td class="p-4 text-center border-l">
                                        <div class="flex flex-col space-y-2">
                                            <form action="{{ route('admin.propositions.approve', $prop->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-2 rounded">Approuver</button>
                                            </form>
                                            <button @click="$dispatch('open-modal-reject', { id: '{{ $prop->id }}' })" class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1 px-2 rounded">Rejeter</button>
                                        </div>

                                        <x-modal-reject :id="$prop->id" :route="route('admin.propositions.reject', $prop->id)" title="Rejeter la proposition" />
                                    </td>

                                @elseif($status === 'rejete')
                                    <td class="p-4 text-red-600 italic">{{ $prop->motif_refus }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">Aucune proposition {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</x-app-layout>
