<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Besoins</h2>
            <a href="{{ route('besoins.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ Nouveau besoin</a>
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
                        <th class="px-6 py-3 text-left">Libellé</th>
                        <th class="px-6 py-3 text-left">Émetteur</th>
                        <th class="px-6 py-3 text-left">Priorité</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Montant (DA)</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($besoins as $besoin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $besoin->libelle }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $besoin->emetteur->nom ?? '—' }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $besoin->priorite === 'haute' ? 'bg-red-100 text-red-600' : ($besoin->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                                {{ ucfirst($besoin->priorite) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $besoin->statut === 'approuve' ? 'bg-green-100 text-green-700' : ($besoin->statut === 'rejete' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ str_replace('_', ' ', $besoin->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">{{ number_format($besoin->montant, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3 text-center flex gap-3 justify-center">
                            <a href="{{ route('besoins.edit', $besoin) }}" class="text-indigo-500 hover:underline text-xs">Modifier</a>
                            <form method="POST" action="{{ route('besoins.destroy', $besoin) }}" onsubmit="return confirm('Supprimer ce besoin ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:underline text-xs">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-6 text-center text-gray-400">Aucun besoin.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $besoins->links() }}</div>
        </div>
    </div>
</x-app-layout>
