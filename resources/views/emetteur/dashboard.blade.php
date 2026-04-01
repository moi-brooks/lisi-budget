<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li class="text-primary font-black">Emetteur</li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary-muted italic">Mon Espace de Travail</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Bienvenue, {{ explode(' ', $emetteur->user->name)[0] }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Info Section -->
            <div class="bg-white border-0 rounded-[40px] p-10 shadow-premium flex flex-col md:flex-row md:items-center justify-between gap-6 overflow-hidden relative">
                <div class="absolute top-0 right-0 -m-8 w-48 h-48 bg-surface-50 rounded-full blur-3xl opacity-50"></div>
                <div class="relative">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted mb-2 italic">Session Budgétaire Active</p>
                    <h3 class="font-display text-xl font-black text-primary tracking-tight">{{ $emetteur->budget->saison }}</h3>
                </div>
                <div class="flex items-center space-x-6 relative">
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted italic mb-1">Exercice Annuel</p>
                        <span class="font-display font-black text-2xl text-accent italic tracking-tighter leading-none">{{ $emetteur->budget->annee }}</span>
                    </div>
                    <div class="h-10 w-px bg-surface-200"></div>
                    <div class="p-3 bg-surface-50 rounded-2xl">
                        <svg class="w-6 h-6 text-primary-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5"/></svg>
                    </div>
                </div>
            </div>

            <!-- Budget Gauge & Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Gauge Card -->
                <div class="lg:col-span-2 bg-white border-0 rounded-[40px] p-10 shadow-premium flex flex-col">
                    @php
                        $engagedTotal = $stats['montant_approuve'] + $stats['montant_attente'];
                        $percent = $stats['dotation'] > 0 ? ($engagedTotal / $stats['dotation']) * 100 : 0;
                        $percent = min($percent, 100);
                        $color = $percent < 70 ? 'bg-status-approved' : ($percent < 90 ? 'bg-amber-500' : 'bg-status-rejected');
                    @endphp
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h3 class="font-display text-xl font-black text-primary tracking-tight">Utilisation des Crédits</h3>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted mt-1 italic">Consommation globale (Estimée + Réelle)</p>
                        </div>
                        <div class="px-4 py-2 {{ str_replace('bg-', 'bg-', $color) }}/10 rounded-full">
                            <span class="text-sm font-black {{ str_replace('bg-', 'text-', $color) }}">{{ number_format($percent, 1) }}%</span>
                        </div>
                    </div>
                    
                    <div class="flex-grow flex flex-col justify-center">
                        <div class="w-full bg-surface-100 rounded-full h-4 overflow-hidden p-1 shadow-inner border border-surface-200/50 mb-10">
                            <div class="{{ $color }} h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $percent }}%"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-2">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-muted italic mb-1">Dotation Ouverte</span>
                                <span class="font-display font-black text-xl text-primary italic tracking-tight">{{ number_format($stats['dotation'], 2, ',', ' ') }} <small class="text-xs uppercase opacity-30">DH</small></span>
                            </div>
                            <div class="flex flex-col border-l border-surface-100 pl-8">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-muted italic mb-1">Total Engagé</span>
                                <span class="font-display font-black text-xl text-primary italic tracking-tight">{{ number_format($engagedTotal, 2, ',', ' ') }} <small class="text-xs uppercase opacity-30">DH</small></span>
                            </div>
                            <div class="flex flex-col border-l border-surface-100 pl-8">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-muted italic mb-1">Reliquat Net</span>
                                <span class="font-display font-black text-xl text-accent italic tracking-tight">{{ number_format($stats['reliquat'], 2, ',', ' ') }} <small class="text-xs uppercase opacity-30">DH</small></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fast Stats -->
                <div class="space-y-6">
                    <x-stat-card label="Approuvé & Validé" value="{{ number_format($stats['montant_approuve'], 0, ',', ' ') }} DH" />
                    <x-stat-card label="En Attente" value="{{ number_format($stats['montant_attente'], 0, ',', ' ') }} DH" />
                </div>
            </div>

            <!-- Action Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Propositions Actions -->
                <div class="bg-white border border-surface-200/50 rounded-[40px] p-10 flex flex-col group hover:shadow-premium transition-all duration-500 overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-10 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity transform group-hover:scale-125 duration-700">
                        <svg class="w-32 h-32 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    </div>
                    <div class="flex items-start justify-between mb-8">
                        <div class="p-3 bg-surface-50 rounded-2xl">
                            <svg class="w-6 h-6 text-primary-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="1.5"/></svg>
                        </div>
                        <span class="bg-surface-50 text-primary text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest">{{ $stats['propositions_count'] }} Dossiers</span>
                    </div>
                    <h3 class="font-display text-2xl font-black text-primary tracking-tight mb-4 italic">Mes Propositions</h3>
                    <p class="text-primary-muted text-xs font-bold leading-relaxed mb-10 italic">Organisez et proposez vos besoins budgétaires pour validation centrale par l'administration.</p>
                    <div class="grid grid-cols-2 gap-4 mt-auto relative z-10">
                        <a href="{{ route('emetteur.lignes.index') }}" class="flex items-center justify-center bg-primary hover:bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl shadow-premium transition-all">Consulter</a>
                        <a href="{{ route('emetteur.lignes.index') }}" class="flex items-center justify-center bg-surface-50 hover:bg-surface-100 text-primary-muted text-[10px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl transition-all italic underline decoration-accent/30 underline-offset-4">Voir les lignes</a>
                    </div>
                </div>

                <!-- Engagements Actions -->
                <div class="bg-white border border-surface-200/50 rounded-[40px] p-10 flex flex-col group hover:shadow-premium transition-all duration-500 overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-10 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity transform group-hover:scale-125 duration-700">
                        <svg class="w-32 h-32 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    </div>
                    <div class="flex items-start justify-between mb-8">
                        <div class="p-3 bg-surface-50 rounded-2xl">
                            <svg class="w-6 h-6 text-primary-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="1.5"/></svg>
                        </div>
                        <span class="bg-surface-50 text-primary text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest">{{ $stats['engagements_count'] }} Engagements</span>
                    </div>
                    <h3 class="font-display text-2xl font-black text-primary tracking-tight mb-4 italic">Bons de Commande</h3>
                    <p class="text-primary-muted text-xs font-bold leading-relaxed mb-10 italic">Émettez vos bons de commande sur vos lignes budgétaires approuvées et suivez les règlements.</p>
                    <div class="grid grid-cols-2 gap-4 mt-auto relative z-10">
                        <a href="{{ route('emetteur.engagements.create') }}" class="flex items-center justify-center bg-accent hover:bg-blue-400 text-white text-[10px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl transition-all shadow-lg shadow-accent/20">Créer un BC</a>
                        <a href="{{ route('emetteur.engagements.index') }}" class="flex items-center justify-center bg-surface-50 hover:bg-surface-100 text-primary text-[10px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl transition-all italic underline underline-offset-4 decoration-accent/30">Historique</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
