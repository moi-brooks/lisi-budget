<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $budget->titre }} <span class="text-gray-400 text-base">({{ $budget->annee }})</span></h2>
            <a href="{{ route('budgets.edit', $budget) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Modifier</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-xl shadow p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <div class="text-xs text-gray-500 uppercase">Statut</div>
                <span class="mt-1 inline-block px-2 py-1 text-sm rounded-full {{ $budget->statut === 'actif' ? 'bg-green-100 text-green-700' : ($budget->statut === 'clos' ? 'bg-gray-200 text-gray-600' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($budget->statut) }}
                </span>
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase">Prévisionnel</div>
                <div class="mt-1 font-semibold text-gray-800">{{ number_format($budget->total_previsionnel, 0, ',', ' ') }} DA</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase">Lignes budgétaires</div>
                <div class="mt-1 font-semibold text-gray-800">{{ $budget->lignes->count() }}</div>
            </div>
            @if($budget->description)
            <div class="col-span-2 md:col-span-4">
                <div class="text-xs text-gray-500 uppercase">Description</div>
                <div class="mt-1 text-gray-700">{{ $budget->description }}</div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b font-semibold text-gray-700">Lignes budgétaires</div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Code</th>
                        <th class="px-6 py-3 text-left">Libellé</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Alloué (DA)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($budget->lignes as $ligne)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $ligne->code }}</td>
                        <td class="px-6 py-3">{{ $ligne->libelle }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $ligne->statut === 'ouvert' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ ucfirst($ligne->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">{{ number_format($ligne->montant_alloue, 0, ',', ' ') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">Aucune ligne budgétaire.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
