<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Expression de besoins') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Gérez et validez les demandes d'achat du laboratoire</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.engagements.export.excel', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition shadow-sm shadow-emerald-200">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </a>
                <a href="{{ route('admin.engagements.export.pdf', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition shadow-sm shadow-rose-200" target="_blank">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabs & Filters Card -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8">
                <div class="flex flex-col gap-6">
                    <!-- Status Tabs -->
                    <nav class="flex p-1 bg-slate-100 rounded-2xl w-fit">
                        <a href="{{ route('admin.engagements.index', array_merge(request()->except('statut'), ['statut' => 'en_attente'])) }}" 
                           class="{{ $status === 'en_attente' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }} px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200">
                            En attente
                        </a>
                        <a href="{{ route('admin.engagements.index', array_merge(request()->except('statut'), ['statut' => 'approuve'])) }}" 
                           class="{{ $status === 'approuve' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }} px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200">
                            Approuvés
                        </a>
                        <a href="{{ route('admin.engagements.index', array_merge(request()->except('statut'), ['statut' => 'rejete'])) }}" 
                           class="{{ $status === 'rejete' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }} px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200">
                            Rejetés
                        </a>
                    </nav>

                    <!-- Advanced Filters Form -->
                    <form method="GET" action="{{ route('admin.engagements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end border-t border-slate-50 pt-6">
                        <input type="hidden" name="statut" value="{{ $status }}">
                        
                        <!-- Année -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Année</label>
                            <select name="annee" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" onchange="this.form.submit()">
                                <option value="">Toutes les années</option>
                                @foreach($annees as $a)
                                    <option value="{{ $a }}" {{ $annee == $a ? 'selected' : '' }}>{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Saison -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Saison</label>
                            <select name="saison" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" onchange="this.form.submit()">
                                <option value="">Toutes les saisons</option>
                                @foreach($saisons as $s)
                                    <option value="{{ $s }}" {{ $saison == $s ? 'selected' : '' }}>Saison {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Émetteur -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Émetteur</label>
                            <select name="emetteur_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" onchange="this.form.submit()">
                                <option value="">Tous les émetteurs</option>
                                @foreach($emetteurs as $e)
                                    <option value="{{ $e->id }}" {{ $emetteur_id == $e->id ? 'selected' : '' }}>{{ $e->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ligne Budgétaire -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Ligne Budgétaire</label>
                            <select name="ligne_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" onchange="this.form.submit()">
                                <option value="">Toutes les lignes</option>
                                @foreach($lignes as $l)
                                    <option value="{{ $l->id }}" {{ $ligne_id == $l->id ? 'selected' : '' }}>{{ $l->code_complet }} - {{ $l->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-3xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-bold tracking-wider border-b border-slate-100">
                                <th class="p-5">Détails de la demande</th>
                                <th class="p-5">Émetteur / Budget</th>
                                <th class="p-5 text-right">Montant TTC</th>
                                <th class="p-5 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($engagements as $engagement)
                                <tr class="hover:bg-slate-50/80 transition duration-150 group">
                                    <td class="p-5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                EB
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800">{{ $engagement->objet }}</div>
                                                <div class="text-xs text-slate-400 mt-0.5">N° {{ str_pad($engagement->id, 5, '0', STR_PAD_LEFT) }} • {{ $engagement->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="font-semibold text-slate-700">{{ $engagement->emetteur->user->name }}</div>
                                        <div class="text-xs text-indigo-500 mt-0.5">Saison {{ $engagement->ligneProposee?->ligne?->budget?->saison ?? '—' }}</div>
                                    </td>
                                    <td class="p-5 text-right font-black text-slate-900">
                                        {{ number_format($engagement->total_ttc, 2, ',', ' ') }} <span class="text-[10px] text-slate-400 font-normal ml-0.5">DH</span>
                                    </td>
                                    
                                    <td class="p-5 text-center">
                                        <a href="{{ route('admin.engagements.show', $engagement) }}" class="inline-flex items-center justify-center bg-white border border-slate-200 group-hover:border-indigo-200 text-slate-600 group-hover:text-indigo-600 px-4 py-2 rounded-xl text-sm transition-all font-bold hover:shadow-sm">
                                            Traiter
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            </div>
                                            <p class="text-slate-500 font-bold">Aucune expression de besoins trouvée</p>
                                            <p class="text-sm text-slate-400 mt-1">Essayez d'ajuster vos filtres pour voir plus de résultats.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
