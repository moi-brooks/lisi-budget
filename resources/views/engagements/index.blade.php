<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Engagements</h2>
            <a href="{{ route('engagements.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ Nouvel engagement</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Émetteur</th>
                        <th class="px-6 py-3 text-left">Ligne / Budget</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Montant (DA)</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($engagements as $eng)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $eng->description }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $eng->emetteur->nom ?? '—' }}</td>
                        <td class="px-6 py-3">
                            <div class="font-mono text-xs text-gray-500">{{ $eng->ligne->code ?? '' }}</div>
                            <div class="text-xs text-gray-400">{{ $eng->ligne->budget->titre ?? '' }}</div>
                        </td>
                        <td class="px-6 py-3">{{ $eng->date_engagement->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $eng->statut === 'solde' ? 'bg-green-100 text-green-700' : ($eng->statut === 'annule' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-700') }}">
                                {{ str_replace('_', ' ', $eng->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">{{ number_format($eng->montant, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('engagements.show', $eng) }}" class="text-indigo-500 hover:underline text-xs">Détails</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-6 text-center text-gray-400">Aucun engagement.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $engagements->links() }}</div>
        </div>
    </div>
</x-app-layout>
