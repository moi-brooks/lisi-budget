<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('emetteur.dashboard') }}" class="hover:text-accent transition-colors">Emetteur</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary font-black">Mes Engagements</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Suivi de mes Bons de Commande (BC)
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

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Tonal Tabs -->
                <div class="flex p-1.5 bg-surface-100 rounded-3xl max-w-fit shadow-sm border border-surface-200/50">
                    <a href="{{ route('emetteur.engagements.index', ['statut' => 'en_attente']) }}" 
                       class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'en_attente' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                        En attente
                    </a>
                    <a href="{{ route('emetteur.engagements.index', ['statut' => 'approuve']) }}" 
                       class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'approuve' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                        Approuvées
                    </a>
                    <a href="{{ route('emetteur.engagements.index', ['statut' => 'rejete']) }}" 
                       class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'rejete' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                        Rejetées
                    </a>
                </div>

                <a href="{{ route('emetteur.engagements.create') }}" 
                    class="group flex items-center space-x-3 px-8 py-4 bg-primary hover:bg-black hover:scale-105 active:scale-95 text-white rounded-[24px] font-bold shadow-premium transition-all duration-300">
                    <svg class="w-5 h-5 transform group-hover:rotate-10 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-sm tracking-tight font-black uppercase">Initialiser un Nouveau BC</span>
                </a>
            </div>

            <x-table-card title="Liste de vos Engagements ({{ ucfirst(str_replace('_', ' ', $status)) }})" search="true">
                <thead>
                    <tr class="bg-surface-50/50 uppercase text-[10px] text-primary-muted font-black tracking-[0.15em] border-b border-surface-100">
                        <th class="px-8 py-5 text-left italic">Date de demande</th>
                        <th class="px-8 py-5 text-left">Objet / Fournisseur</th>
                        <th class="px-8 py-5 text-left">Ligne Imputée</th>
                        <th class="px-8 py-5 text-right">Montant Total</th>
                        <th class="px-8 py-5 text-right">Détails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @forelse($engagements as $engagement)
                        <tr class="group hover:bg-surface-50/50 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <span class="text-primary-muted text-[10px] font-black uppercase tracking-widest italic font-bold leading-none">{{ $engagement->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-display font-extrabold text-primary group-hover:text-accent transition-colors leading-tight italic tracking-tight">
                                    {{ Str::limit($engagement->objet ?? $engagement->commentaire, 45) }}
                                </div>
                                <div class="text-[10px] text-primary-muted font-black uppercase tracking-[0.15em] mt-1">{{ $engagement->fournisseur?->nom ?? 'FOURNISSEUR NON DÉFINI' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="px-2 py-1 bg-surface-100 rounded text-[9px] font-black text-primary-muted font-mono tracking-tighter italic">
                                        {{ $engagement->ligneProposee->ligne->code_complet }}
                                    </div>
                                    <span class="text-primary text-xs font-extrabold leading-none line-clamp-1 max-w-[150px] italic">{{ $engagement->ligneProposee->ligne->nom }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-display font-black text-primary text-lg leading-none italic tracking-tighter">
                                        {{ number_format($engagement->montant_total, 2, ',', ' ') }}
                                    </span>
                                    <span class="text-[10px] font-black text-primary-muted uppercase tracking-widest mt-1 italic">DH <small class="text-slate-300 uppercase leading-none italic">TTC</small></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('emetteur.engagements.show', $engagement) }}" 
                                    class="inline-flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.2em] text-accent hover:text-blue-700 transition-all duration-300 group/btn">
                                    <span>{{ $engagement->statut === 'en_attente' ? 'Finaliser' : 'Voir' }}</span>
                                    <svg class="w-3.5 h-3.5 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-primary-muted italic text-sm font-black uppercase tracking-widest opacity-30">
                                Aucun bon de commande {{ str_replace('_', ' ', $status) }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table-card>
        </div>
    </div>
</x-app-layout>
