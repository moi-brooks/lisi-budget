<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Budgets') }}
            </h2>
            <a href="{{ route('admin.budgets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                Nouveau Budget
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-sm text-gray-600 border-b">
                            <th class="p-4">Année</th>
                            <th class="p-4">Saison</th>
                            <th class="p-4">Total (DH)</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-semibold">{{ $budget->annee }}</td>
                                <td class="p-4">{{ $budget->saison }}</td>
                                <td class="p-4">{{ number_format($budget->total, 2, ',', ' ') }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('admin.budgets.edit', $budget) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                                        <form action="{{ route('admin.budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">Aucun budget défini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
