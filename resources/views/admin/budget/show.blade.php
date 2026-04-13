<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Budget : ' . $budget->saison) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <span class="block text-sm text-gray-500">Année</span>
                        <span class="block font-bold text-lg">{{ $budget->annee }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Saison</span>
                        <span class="block font-bold text-lg">{{ $budget->saison }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Total (DH)</span>
                        <span class="block font-bold text-lg text-blue-600">{{ number_format($budget->total, 2, ',', ' ') }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Reliquat non alloué</span>
                        <span class="block font-bold text-lg {{ $budget->reliquat < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($budget->reliquat, 2, ',', ' ') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Émetteurs rattachés</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-sm text-gray-600 border-b">
                            <th class="p-3">Émetteur</th>
                            <th class="p-3">Dotation (DH)</th>
                            <th class="p-3">Consommé</th>
                            <th class="p-3">Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budget->emetteurs as $emetteur)
                            <tr class="border-b">
                                <td class="p-3 font-semibold">{{ $emetteur->user->name }}</td>
                                <td class="p-3">{{ number_format($emetteur->dotation, 2, ',', ' ') }}</td>
                                <td class="p-3">{{ number_format($emetteur->montant_approuve, 2, ',', ' ') }}</td>
                                <td class="p-3 font-bold">{{ number_format($emetteur->reliquat, 2, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">Aucun émetteur rattaché à ce budget.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <a href="{{ route('admin.budgets.lignes.index', $budget) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                    Gérer les rubriques
                </a>

                <a href="{{ route('admin.budgets.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-medium transition">← Retour à la liste</a>
            </div>
        </div>
    </div>
</x-app-layout>
