<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Lignes Budgétaires — {{ $budget->annee }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Gérez les rubriques de dépenses associées à ce budget.</p>
            </div>
            <a href="{{ route('admin.budgets.lignes.create', $budget) }}"
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                + Nouvelle Ligne
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 w-12">#</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Nom de la Rubrique</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Code Ligne</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($lignes as $index => $ligne)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4 text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">{{ $ligne->nom }}</td>
                                <td class="px-6 py-4 text-sm font-mono text-slate-500">{{ $ligne->code_ligne }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.budgets.lignes.edit', [$budget, $ligne]) }}"
                                           class="text-xs font-semibold text-indigo-600 hover:underline">Modifier</a>
                                        <form method="POST" action="{{ route('admin.budgets.lignes.destroy', [$budget, $ligne]) }}"
                                              onsubmit="return confirm('Supprimer cette ligne ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm italic">
                                    Aucune ligne budgétaire pour ce budget. Créez-en une.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('admin.budgets.show', $budget) }}"
                   class="text-sm text-slate-500 hover:text-slate-800 font-medium transition">
                    ← Retour au budget
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
