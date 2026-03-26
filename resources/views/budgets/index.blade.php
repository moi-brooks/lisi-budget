<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Budgets</h2>
            <a href="{{ route('budgets.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ Nouveau budget</a>
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
                        <th class="px-6 py-3 text-left">Titre</th>
                        <th class="px-6 py-3 text-left">Année</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Prévisionnel (DA)</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($budgets as $budget)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3"><a href="{{ route('budgets.show', $budget) }}" class="text-indigo-600 hover:underline font-medium">{{ $budget->titre }}</a></td>
                        <td class="px-6 py-3">{{ $budget->annee }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $budget->statut === 'actif' ? 'bg-green-100 text-green-700' : ($budget->statut === 'clos' ? 'bg-gray-200 text-gray-600' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($budget->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">{{ number_format($budget->total_previsionnel, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('budgets.edit', $budget) }}" class="text-indigo-500 hover:underline text-xs">Modifier</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-6 text-center text-gray-400">Aucun budget trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $budgets->links() }}</div>
        </div>
    </div>
</x-app-layout>
