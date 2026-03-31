<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow p-5 text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['budgets_actifs'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Budgets actifs</div>
                </div>
                <div class="bg-white rounded-xl shadow p-5 text-center">
                    <div class="text-3xl font-bold text-amber-500">{{ $stats['besoins_en_attente'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Besoins en attente</div>
                </div>
                <div class="bg-white rounded-xl shadow p-5 text-center">
                    <div class="text-3xl font-bold text-blue-500">{{ $stats['engagements_cours'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Engagements en cours</div>
                </div>
                <div class="bg-white rounded-xl shadow p-5 text-center">
                    <div class="text-3xl font-bold text-emerald-600">{{ number_format($stats['total_engage'], 0, ',', ' ') }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total engagé (DA)</div>
                </div>
            </div>

            {{-- Recent Budgets --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isGestionnaire())
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-gray-700">Budgets récents</h3>
                    <a href="{{ route('budgets.index') }}" class="text-indigo-600 text-sm hover:underline">Voir tout</a>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Titre</th>
                            <th class="px-6 py-3 text-left">Année</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-right">Prévisionnel (DA)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentBudgets as $budget)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3"><a href="{{ route('budgets.show', $budget) }}" class="text-indigo-600 hover:underline">{{ $budget->titre }}</a></td>
                            <td class="px-6 py-3">{{ $budget->annee }}</td>
                            <td class="px-6 py-3 text-center">
                                <x-status-badge :status="$budget->statut" />
                            </td>

                            <td class="px-6 py-3 text-right">{{ number_format($budget->total_previsionnel, 0, ',', ' ') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">Aucun budget.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Recent Besoins --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-gray-700">Besoins récents</h3>
                    <a href="{{ route('besoins.index') }}" class="text-indigo-600 text-sm hover:underline">Voir tout</a>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Libellé</th>
                            <th class="px-6 py-3 text-left">Émetteur</th>
                            <th class="px-6 py-3 text-left">Priorité</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-right">Montant (DA)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentBesoins as $besoin)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $besoin->libelle }}</td>
                            <td class="px-6 py-3">{{ $besoin->emetteur->nom ?? '—' }}</td>
                            <td class="px-6 py-3 capitalize">{{ $besoin->priorite }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $besoin->statut === 'approuve' ? 'bg-green-100 text-green-700' : ($besoin->statut === 'rejete' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ str_replace('_', ' ', $besoin->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">{{ number_format($besoin->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-400">Aucun besoin.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
