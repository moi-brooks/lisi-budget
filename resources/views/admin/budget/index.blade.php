<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Gestion des Budgets') }}
            </h2>
            <a href="{{ route('admin.budgets.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300 shadow-sm">
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

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                                <th class="p-4">Année budgétaire</th>
                                <th class="p-4">Dotation annuelle (DH)</th>
                                <th class="p-4">Date Limite</th>
                                <th class="p-4 text-center">Statut</th>
                                <th class="p-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($budgets as $budget)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                    <td class="p-4 font-semibold text-slate-800">{{ $budget->annee }}</td>
                                    <td class="p-4 font-medium text-slate-700">{{ number_format($budget->total, 2, ',', ' ') }}</td>
                                    <td class="p-4 text-slate-600">
                                        {{ $budget->date_limite ? $budget->date_limite->format('d/m/Y') : 'Aucune' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($budget->is_closed)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Clôturé</span>
                                        @else
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">Actif</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('admin.budgets.show', $budget) }}" class="inline-flex items-center justify-center bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Voir</a>
                                            <a href="{{ route('admin.budgets.edit', $budget) }}" class="inline-flex items-center justify-center bg-amber-50 text-amber-700 hover:bg-amber-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Modifier</a>
                                            <form action="{{ route('admin.budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 font-medium">Aucun budget défini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
