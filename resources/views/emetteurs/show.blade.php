<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $emetteur->nom }} <span class="text-gray-400 text-base font-mono">({{ $emetteur->code }})</span></h2>
            <a href="{{ route('emetteurs.edit', $emetteur) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Modifier</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="text-sm text-gray-500">Responsable</div>
            <div class="mt-1 font-semibold text-gray-800">{{ $emetteur->user->name }} ({{ $emetteur->user->email }})</div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b font-semibold text-gray-700">Besoins ({{ $emetteur->besoins->count() }})</div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Libellé</th>
                        <th class="px-6 py-3 text-left">Priorité</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Montant (DA)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($emetteur->besoins as $besoin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $besoin->libelle }}</td>
                        <td class="px-6 py-3 capitalize">{{ $besoin->priorite }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $besoin->statut === 'approuve' ? 'bg-green-100 text-green-700' : ($besoin->statut === 'rejete' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ str_replace('_', ' ', $besoin->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">{{ number_format($besoin->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">Aucun besoin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b font-semibold text-gray-700">Engagements ({{ $emetteur->engagements->count() }})</div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Ligne budgétaire</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Montant (DA)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($emetteur->engagements as $eng)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $eng->description }}</td>
                        <td class="px-6 py-3 font-mono text-xs">{{ $eng->ligne->code ?? '—' }}</td>
                        <td class="px-6 py-3">{{ $eng->date_engagement->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $eng->statut === 'solde' ? 'bg-green-100 text-green-700' : ($eng->statut === 'annule' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-700') }}">
                                {{ str_replace('_', ' ', $eng->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">{{ number_format($eng->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-400">Aucun engagement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
