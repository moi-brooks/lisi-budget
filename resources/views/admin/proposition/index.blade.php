<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-accent transition-colors">Admin</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary font-black">Propositions</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Arbitrage des Propositions
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
            @if(session('error'))
                <div class="p-4 bg-status-rejected-bg border-0 rounded-2xl text-status-rejected text-sm font-bold animate-in fade-in slide-in-from-top-2 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tonal Tabs -->
            <div class="flex p-1.5 bg-surface-100 rounded-3xl max-w-fit shadow-sm border border-surface-200/50">
                <a href="{{ route('admin.propositions.index', ['statut' => 'en_attente']) }}" 
                   class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'en_attente' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                    En attente
                </a>
                <a href="{{ route('admin.propositions.index', ['statut' => 'approuve']) }}" 
                   class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'approuve' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                    Approuvées
                </a>
                <a href="{{ route('admin.propositions.index', ['statut' => 'rejete']) }}" 
                   class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'rejete' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                    Rejetées
                </a>
            </div>

            <x-table-card title="Liste des Propositions ({{ ucfirst(str_replace('_', ' ', $status)) }})" search="true">
                <thead>
                    <tr class="bg-surface-50/50 uppercase text-[10px] text-primary-muted font-black tracking-[0.15em] border-b border-surface-100">
                        <th class="px-8 py-5 text-left italic">Date de dépôt</th>
                        <th class="px-8 py-5 text-left">Émetteur / Justification</th>
                        <th class="px-8 py-5 text-left">Ligne Budgétaire</th>
                        <th class="px-8 py-5 text-right">Montant</th>
                        @if($status === 'en_attente')
                            <th class="px-8 py-5 text-right">Décision</th>
                        @elseif($status === 'rejete')
                            <th class="px-8 py-5 text-left">Motif de rejet</th>
                        @else
                            <th class="px-8 py-5 text-right">Statut</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @forelse($propositions as $prop)
                        <tr class="group hover:bg-surface-50/50 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <span class="text-primary-muted text-[10px] font-black uppercase tracking-widest italic">{{ $prop->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-display font-extrabold text-primary group-hover:text-accent transition-colors">{{ $prop->emetteur->user->name }}</div>
                                <div class="text-[11px] text-primary-muted font-bold italic mt-0.5 line-clamp-1 max-w-xs">{{ $prop->justification }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="px-2 py-1 bg-surface-100 rounded text-[9px] font-black text-primary-muted font-mono tracking-tighter italic">
                                        {{ $prop->ligne->code_complet }}
                                    </div>
                                    <span class="text-primary font-bold text-sm tracking-tight">{{ $prop->ligne->nom }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-display font-black text-primary text-lg leading-none italic tracking-tighter">
                                        {{ number_format($prop->montant, 0, ',', ' ') }}
                                    </span>
                                    <span class="text-[10px] font-black text-primary-muted uppercase tracking-widest mt-1 italic leading-none">DH</span>
                                </div>
                            </td>
                            
                            @if($status === 'en_attente')
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-6">
                                        <form action="{{ route('admin.propositions.approve', $prop->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                class="text-[10px] font-black uppercase tracking-[0.15em] text-status-approved hover:scale-110 transition-transform active:scale-95">
                                                Approuver
                                            </button>
                                        </form>
                                        <button @click="$dispatch('open-modal-reject', { id: '{{ $prop->id }}' })" 
                                            class="text-[10px] font-black uppercase tracking-[0.15em] text-status-rejected hover:scale-110 transition-transform active:scale-95">
                                            Rejeter
                                        </button>
                                        <x-modal-reject :id="$prop->id" :route="route('admin.propositions.reject', $prop->id)" title="Défavoriser la proposition" />
                                    </div>
                                </td>
                            @elseif($status === 'rejete')
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-2 text-status-rejected bg-status-rejected-bg px-4 py-2 rounded-2xl max-w-fit shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" /></svg>
                                        <span class="text-[11px] font-black italic tracking-tight leading-none uppercase">{{ $prop->motif_refus }}</span>
                                    </div>
                                </td>
                            @else
                                <td class="px-8 py-6 text-right">
                                    <x-status-badge :status="$prop->statut" />
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-surface-50 rounded-3xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-primary-muted opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5" /></svg>
                                    </div>
                                    <p class="text-primary-muted text-sm font-black italic uppercase tracking-widest">Aucune proposition {{ str_replace('_', ' ', $status) }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table-card>
        </div>
    </div>
</x-app-layout>
