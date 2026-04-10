<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Mes Expressions de Besoins') }}
            </h2>
            <a href="{{ route('emetteur.engagements.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition duration-300 shadow-sm text-sm inline-flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Expression de Besoins
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

            <!-- Tabs -->
            <div class="mb-8">
                <nav class="flex space-x-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 max-w-fit">
                    <a href="{{ route('emetteur.engagements.index', ['statut' => 'en_attente']) }}" class="{{ $status === 'en_attente' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        En attente
                    </a>
                    <a href="{{ route('emetteur.engagements.index', ['statut' => 'approuve']) }}" class="{{ $status === 'approuve' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Approuvés
                    </a>
                    <a href="{{ route('emetteur.engagements.index', ['statut' => 'rejete']) }}" class="{{ $status === 'rejete' ? 'bg-rose-50 text-rose-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Rejetés
                    </a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                            <th class="p-4">Date</th>
                            <th class="p-4">Commentaire / Objet</th>
                            <th class="p-4">Ligne Imputée</th>
                            <th class="p-4">Montant Estimé (TTC)</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engagements as $engagement)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                <td class="p-4 text-slate-600">{{ $engagement->created_at->format('d/m/Y') }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-700">{{ Str::limit($engagement->commentaire ?? 'Aucun commentaire', 40) }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $engagement->fournisseur?->nom ?? 'Fournisseur non défini' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-mono text-xs text-slate-400 mb-1 border border-slate-200 inline-block px-1.5 py-0.5 rounded bg-slate-50">{{ $engagement->ligneProposee->ligne->code_complet }}</div>
                                </td>
                                <td class="p-4 font-bold text-indigo-600">{{ number_format($engagement->montant_total, 2, ',', ' ') }} DH</td>
                                
                                <td class="p-4 text-center">
                                    <a href="{{ route('emetteur.engagements.show', $engagement) }}" class="inline-flex items-center justify-center bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Voir / Compléter</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500 font-medium">Aucune expression de besoins {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
