<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-accent transition-colors">Admin</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary font-black">Budgets</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Gestion des Budgets Annuels
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-status-approved-bg border-0 rounded-2xl text-status-approved text-sm font-bold animate-in fade-in slide-in-from-top-2 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('admin.budgets.create') }}" 
                    class="group flex items-center space-x-3 px-8 py-4 bg-primary hover:bg-black hover:scale-105 active:scale-95 text-white rounded-[24px] font-bold shadow-premium transition-all duration-300">
                    <svg class="w-5 h-5 transform group-hover:rotate-10 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-sm tracking-tight font-black uppercase">Ouvrir un Nouvel Exercice</span>
                </a>
            </div>

            <x-table-card title="Liste des Exercices Budgétaires" search="true">
                <thead>
                    <tr class="bg-surface-50/50 uppercase text-[10px] text-primary-muted font-black tracking-[0.15em] border-b border-surface-100">
                        <th class="px-8 py-5 text-left text-accent italic">Année fiscale</th>
                        <th class="px-8 py-5 text-left">Saison / Libellé</th>
                        <th class="px-8 py-5 text-right">Dotation Totale (TTC)</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @forelse($budgets as $budget)
                        <tr class="group hover:bg-surface-50/50 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <span class="font-display font-black text-primary group-hover:text-accent transition-colors text-3xl leading-none italic tracking-tighter">
                                    {{ $budget->annee }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-extrabold text-primary uppercase tracking-tight">{{ $budget->saison }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-display font-black text-primary text-xl leading-none italic tracking-tighter">
                                        {{ number_format($budget->total, 0, ',', ' ') }}
                                    </span>
                                    <span class="text-[10px] font-black text-primary-muted uppercase tracking-widest mt-1 italic leading-none">DH</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-6">
                                    <a href="{{ route('admin.budgets.show', $budget) }}" 
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-accent hover:text-blue-700 transition-all duration-300">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.budgets.edit', $budget) }}" 
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-600 hover:text-amber-700 transition-all duration-300">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Attention: Cette action supprimera également les données liées !');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black uppercase tracking-[0.2em] text-status-rejected hover:text-red-700 transition-all duration-300">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-primary-muted italic text-sm font-black uppercase tracking-widest opacity-30">
                                Aucun budget défini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table-card>
        </div>
    </div>
</x-app-layout>
