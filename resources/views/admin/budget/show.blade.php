<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budget — Année ' . $budget->annee) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow-sm rounded-2xl p-5 border border-slate-100">
                    <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Année budgétaire</span>
                    <span class="block font-bold text-2xl text-slate-800">{{ $budget->annee }}</span>
                </div>
                <div class="bg-white shadow-sm rounded-2xl p-5 border border-slate-100">
                    <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Dotation annuelle</span>
                    <span class="block font-bold text-2xl text-indigo-600">{{ number_format($budget->total, 2, ',', ' ') }} DH</span>
                </div>
                <div class="bg-white shadow-sm rounded-2xl p-5 border border-slate-100">
                    <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Budget validé</span>
                    @php $totalValide = \App\Models\Engagement::where('statut','approuve')->whereHas('ligneProposee.ligne', fn($q) => $q->where('budget_id', $budget->id))->sum('total_ttc'); @endphp
                    <span class="block font-bold text-2xl text-emerald-600">{{ number_format($totalValide, 2, ',', ' ') }} DH</span>
                </div>
                <div class="bg-white shadow-sm rounded-2xl p-5 border border-slate-100">
                    <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Reliquat non alloué</span>
                    <span class="block font-bold text-2xl {{ $budget->reliquat < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($budget->reliquat, 2, ',', ' ') }} DH</span>
                </div>
            </div>

            <!-- Emetteurs table -->
            <div class="bg-white shadow-sm rounded-2xl p-6 mb-6 border border-slate-100">
                <h3 class="text-lg font-bold mb-4 text-slate-800">Émetteurs rattachés</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                            <th class="p-3">Émetteur</th>
                            <th class="p-3">Dotation annuelle (DH)</th>
                            <th class="p-3">Budget validé</th>
                            <th class="p-3">Reliquat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budget->emetteurs as $emetteur)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="p-3 font-semibold text-slate-800">{{ $emetteur->user?->name ?? '—' }}</td>
                                <td class="p-3 text-slate-600">{{ number_format($emetteur->dotation, 2, ',', ' ') }}</td>
                                <td class="p-3 text-emerald-600 font-semibold">{{ number_format($emetteur->montant_approuve, 2, ',', ' ') }}</td>
                                <td class="p-3 font-bold {{ $emetteur->reliquat < 0 ? 'text-red-600' : 'text-slate-800' }}">{{ number_format($emetteur->reliquat, 2, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-slate-400 italic text-sm">Aucun émetteur rattaché à ce budget.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.budgets.lignes.index', $budget) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                    Gérer les rubriques
                </a>
                <a href="{{ route('exports.besoins', $budget) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow transition">
                    Exporter DOCX (Expression des besoins)
                </a>
                <a href="{{ route('exports.excel', $budget) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow transition">
                    Exporter Excel
                </a>
                <a href="{{ route('admin.budgets.index') }}" class="text-slate-500 hover:text-slate-800 text-sm font-medium transition">← Retour à la liste</a>
            </div>

        </div>
    </div>
</x-app-layout>
