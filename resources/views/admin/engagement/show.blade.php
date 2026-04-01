<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-primary/10 rounded-2xl">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <li><a href="{{ route('admin.engagements.index') }}" class="hover:text-primary transition-colors">Engagements</a></li>
                        <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-slate-600">Détails du BC</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Bon de Commande #{{ $engagement->id }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 rounded-2xl flex items-center space-x-3 text-emerald-800 animate-in fade-in slide-in-from-top-4 duration-300">
                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Summary Hero Card --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 relative group overflow-hidden bg-gradient-to-br from-primary to-indigo-900 rounded-[48px] p-10 text-white shadow-2xl shadow-primary/20">
                    <div class="absolute top-0 right-0 p-12 opacity-10 transform translate-x-12 -translate-y-12">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9l-7-7z"/><path d="M13 3v6h7"/></svg>
                    </div>

                    <div class="relative z-10 space-y-8">
                        <div>
                            <x-status-badge :status="$engagement->statut" class="bg-white/20 text-white border-0 backdrop-blur-md mb-6" />
                            <h3 class="text-4xl font-display font-bold tracking-tight mb-2">{{ $engagement->commentaire ?? 'Détails de l\'engagement' }}</h3>
                            <p class="text-white/70 text-lg">Émis par : <span class="text-white font-semibold">{{ $engagement->emetteur?->user?->name ?? 'Inconnu' }}</span> le {{ $engagement->created_at?->format('d/m/Y') }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-white/10">
                            <div>
                                <span class="block text-xs font-bold text-white/50 uppercase tracking-widest mb-2">Fournisseur</span>
                                <span class="text-xl font-semibold">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-white/50 uppercase tracking-widest mb-2">Paiement Partiel</span>
                                <span class="text-xl font-semibold">{{ $engagement->paiement_partiel ? 'Oui' : 'Non' }}</span>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-white/10">
                            <span class="block text-xs font-bold text-white/50 uppercase tracking-widest mb-3">Imputation Budgétaire</span>
                            <div class="inline-flex items-center space-x-3 bg-white/10 p-3 rounded-2xl backdrop-blur-sm">
                                <span class="px-2 py-1 bg-white text-primary rounded-lg font-mono text-xs font-bold">{{ $engagement->ligneProposee?->ligne?->code_complet }}</span>
                                <span class="text-sm font-medium">{{ $engagement->ligneProposee?->ligne?->nom }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Financial Summary Card --}}
                <div class="bg-surface-container-lowest rounded-[48px] p-10 flex flex-col justify-between border-0 shadow-sm border-slate-100 h-full">
                    <div class="space-y-8">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Détails Financiers</span>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-500">TVA Globale</span>
                                    <span class="font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded-lg">{{ $engagement->tva }}%</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-500">Total HT</span>
                                    <span class="font-semibold text-slate-700">{{ number_format($engagement->total_ht, 2, ',', ' ') }} DH</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-10 border-t border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Montant Total TTC Estimé</span>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-5xl font-display font-black text-primary tracking-tighter">{{ number_format($engagement->total_ttc, 2, ',', ' ') }}</span>
                                <span class="text-xl font-bold text-primary/40 uppercase tracking-widest">DH</span>
                            </div>
                        </div>
                    </div>

                    @if($engagement->statut === 'rejete')
                        <div class="mt-8 p-6 bg-rose-50 rounded-[32px] border-0">
                            <span class="block text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-2">Motif du rejet</span>
                            <p class="text-sm text-rose-800 font-medium leading-relaxed italic">"{{ $engagement->motif_refus }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Articles Section --}}
            <div class="bg-surface-container-low rounded-[48px] overflow-hidden border-0">
                <div class="p-8 px-10 flex justify-between items-center bg-white/50 backdrop-blur-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-xl font-display font-bold text-slate-900 tracking-tight">Articles & Besoins liés</h3>
                    </div>
                    <span class="px-4 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-widest">{{ $engagement->besoins->count() }} Articles</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest">Désignation</th>
                                <th class="px-6 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-center">Quantité</th>
                                <th class="px-6 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-right">PU HT</th>
                                <th class="px-10 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-right">Total HT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @forelse($engagement->besoins as $besoin)
                                <tr class="group hover:bg-white transition-colors duration-200">
                                    <td class="px-10 py-6">
                                        <div class="font-display font-bold text-slate-900 text-base mb-1">{{ $besoin->intitule }}</div>
                                        @if($besoin->description)
                                            <div class="text-sm text-slate-500 max-w-md line-clamp-1 group-hover:line-clamp-none transition-all">{{ $besoin->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 rounded-xl font-bold text-slate-700 text-sm">
                                            {{ $besoin->quantite }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-right font-medium text-slate-600">
                                        {{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="text-lg font-display font-bold text-primary group-hover:scale-110 transition-transform inline-block">
                                            {{ number_format($besoin->montant, 2, ',', ' ') }} <small class="text-[10px] opacity-60">DH</small>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-10 py-20 text-center">
                                        <div class="flex flex-col items-center space-y-4">
                                            <div class="p-4 bg-slate-50 rounded-full">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </div>
                                            <p class="text-slate-400 font-medium italic">Aucun article dans ce bon de commande.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Administration Decision Logic --}}
            @if($engagement->statut === 'en_attente')
                <div class="relative group bg-surface-container-lowest rounded-[48px] p-10 border-0 shadow-2xl shadow-indigo-900/5 overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-primary/20"></div>
                    
                    <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
                        <div class="space-y-2">
                            <h4 class="font-display text-2xl font-bold text-slate-900 tracking-tight">Décision Administrative</h4>
                            <p class="text-slate-500 max-w-lg leading-relaxed font-medium">Veuillez examiner ce bon de commande. Votre décision impactera les soldes budgétaires restants.</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                            <form action="{{ route('admin.engagements.approve', $engagement->id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" 
                                    class="group relative w-full sm:w-auto bg-primary hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 shadow-lg shadow-primary/25 active:scale-95 flex items-center justify-center space-x-2"
                                    onclick="return confirm('Confirmer l\'approbation de ce BC ?');">
                                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Approuver le BC</span>
                                </button>
                            </form>
                            
                            <button @click="$dispatch('open-modal-reject', { id: '{{ $engagement->id }}' })" 
                                class="w-full sm:w-auto bg-white hover:bg-rose-50 text-rose-600 border-2 border-rose-100 hover:border-rose-200 font-bold py-4 px-8 rounded-2xl transition-all active:scale-95 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Rejeter</span>
                            </button>
                        </div>
                    </div>
                </div>

                <x-modal-reject :id="$engagement->id" :route="route('admin.engagements.reject', $engagement->id)" title="Rejeter le bon de commande" />
            @endif

            {{-- Footer Actions --}}
            <div class="flex items-center justify-center pt-8">
                <a href="{{ route('admin.engagements.index') }}" class="group flex items-center space-x-2 text-slate-400 hover:text-primary transition-colors duration-300 font-bold tracking-widest text-[10px] uppercase">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Retour à la liste des engagements</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
