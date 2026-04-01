<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-accent transition-colors">Admin</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary font-black">Engagements</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Suivi des Engagements (BC)
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
                <a href="{{ route('admin.engagements.index', ['statut' => 'en_attente']) }}" 
                   class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'en_attente' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                    En attente
                </a>
                <a href="{{ route('admin.engagements.index', ['statut' => 'approuve']) }}" 
                   class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'approuve' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                    Approuvés
                </a>
                <a href="{{ route('admin.engagements.index', ['statut' => 'rejete']) }}" 
                   class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 {{ $status === 'rejete' ? 'bg-white text-primary shadow-premium' : 'text-primary-muted hover:text-primary hover:bg-white/50' }}">
                    Rejetées
                </a>
            </div>

            <x-table-card title="Liste des Engagements ({{ ucfirst(str_replace('_', ' ', $status)) }})" search="true">
                <thead>
                    <tr class="bg-surface-50/50 uppercase text-[10px] text-primary-muted font-black tracking-[0.15em] border-b border-surface-100">
                        <th class="px-8 py-5 text-left italic">Date</th>
                        <th class="px-8 py-5 text-left">Émetteur / Fournisseur</th>
                        <th class="px-8 py-5 text-left">Objet / Commentaire</th>
                        <th class="px-8 py-5 text-right">Montant Total</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @forelse($engagements as $engagement)
                        <tr class="group hover:bg-surface-50/50 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <span class="text-primary-muted text-[10px] font-black uppercase tracking-widest italic leading-none">{{ $engagement->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-display font-extrabold text-primary group-hover:text-accent transition-colors">{{ $engagement->emetteur->user->name }}</div>
                                <div class="text-[10px] text-primary-muted font-black uppercase tracking-[0.15em] mt-1">{{ $engagement->fournisseur?->nom ?? 'FOURNISSEUR NON DÉFINI' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-primary text-sm font-bold italic line-clamp-1 max-w-xs">{{ Str::limit($engagement->objet ?? $engagement->commentaire, 50) }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-display font-black text-primary text-lg leading-none italic tracking-tighter">
                                        {{ number_format($engagement->montant_total, 2, ',', ' ') }}
                                    </span>
                                    <span class="text-[10px] font-black text-primary-muted uppercase tracking-widest mt-1 italic">DH <small class="text-slate-300">TTC</small></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.engagements.show', $engagement) }}" 
                                    class="inline-flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.2em] text-accent hover:text-blue-700 transition-all duration-300 group/btn">
                                    <span>Gérer</span>
                                    <svg class="w-3.5 h-3.5 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-surface-50 rounded-3xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-primary-muted opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="1.5" /></svg>
                                    </div>
                                    <p class="text-primary-muted text-sm font-black italic uppercase tracking-widest">Aucun engagement {{ str_replace('_', ' ', $status) }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table-card>
        </div>
    </div>
</x-app-layout>
