<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('emetteur.dashboard') }}" class="hover:text-accent transition-colors">Emetteur</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary font-black">Mes Lignes Budgétaires</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Affectations & Crédits Disponibles
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

            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3 bg-surface-100 px-6 py-3 rounded-2xl border border-surface-200/50">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted italic">Exercice Actif:</span>
                    <span class="text-primary font-display font-black text-lg italic tracking-tighter leading-none">{{ $emetteur->budget->annee }}</span>
                </div>

                <a href="{{ route('emetteur.lignes.index') }}" 
                    class="group flex items-center space-x-3 px-8 py-4 bg-primary hover:bg-black hover:scale-105 active:scale-95 text-white rounded-[24px] font-bold shadow-premium transition-all duration-300">
                    <svg class="w-5 h-5 transform group-hover:-rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h11m-11 4h11m-11 4h11" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-sm tracking-tight font-black uppercase">Consulter mes Propositions</span>
                </a>
            </div>

            <x-table-card title="Détail des Lignes Budgétaires" search="true">
                <thead>
                    <tr class="bg-surface-50/50 uppercase text-[10px] text-primary-muted font-black tracking-[0.15em] border-b border-surface-100">
                        <th class="px-8 py-5 text-left italic">Référence / Code</th>
                        <th class="px-8 py-5 text-left">Désignation de la Ligne</th>
                        <th class="px-8 py-5 text-right">Crédit Ouvert (HT)</th>
                        <th class="px-8 py-5 text-right">Action Directe</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @forelse($lignes as $ligne)
                        <tr class="group hover:bg-surface-50/50 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 bg-surface-100 rounded text-[10px] font-black text-primary-muted font-mono tracking-tighter italic">
                                    {{ $ligne->code_complet }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-display font-extrabold text-primary group-hover:text-accent transition-colors leading-tight italic tracking-tight text-sm">
                                    {{ $ligne->nom }}
                                </div>
                                <div class="text-[10px] text-primary-muted font-black uppercase tracking-[0.1em] mt-1">{{ $ligne->type_ligne }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-display font-black text-primary text-xl leading-none italic tracking-tighter">
                                        {{ number_format($ligne->credit_ouvert, 2, ',', ' ') }}
                                    </span>
                                    <span class="text-[10px] font-black text-primary-muted uppercase tracking-widest mt-1 italic">DH <small class="text-slate-300 italic">HT</small></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('emetteur.lignes.create', ['ligne_id' => $ligne->id]) }}" 
                                    class="inline-flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.2em] text-accent hover:text-blue-700 transition-all duration-300 group/btn">
                                    <span>Demander une Provision</span>
                                    <svg class="w-3.5 h-3.5 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-primary-muted italic text-sm font-black uppercase tracking-widest opacity-30">
                                Aucune ligne budgétaire affectée pour cet exercice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table-card>
        </div>
    </div>
</x-app-layout>
