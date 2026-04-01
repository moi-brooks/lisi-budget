<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-primary/10 rounded-2xl">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <li><a href="{{ route('admin.budgets.index') }}" class="hover:text-primary transition-colors">Budgets</a></li>
                        <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-slate-600">Détails du Budget</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    {{ __('Budget : ' . $budget->saison) }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Budget Hero Card --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-3 relative overflow-hidden bg-gradient-to-br from-primary to-indigo-900 rounded-[48px] p-12 text-white shadow-2xl shadow-primary/20">
                    <div class="absolute top-0 right-0 p-12 opacity-10 transform translate-x-12 -translate-y-12">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>

                    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div class="space-y-2">
                            <span class="block text-[10px] font-bold text-white/40 uppercase tracking-widest">Saison & Année</span>
                            <h3 class="text-4xl font-display font-black tracking-tight">{{ $budget->saison }}</h3>
                            <p class="text-white/60 font-medium">Exercice budgétaire {{ $budget->annee }}</p>
                        </div>

                        <div class="space-y-2">
                            <span class="block text-[10px] font-bold text-white/40 uppercase tracking-widest">Dotation Totale</span>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-4xl font-display font-black text-white tracking-tight">{{ number_format($budget->total, 0, ',', ' ') }}</span>
                                <span class="text-lg font-bold text-white/40">DH</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <span class="block text-[10px] font-bold text-white/40 uppercase tracking-widest">Reliquat non-alloué</span>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-4xl font-display font-black {{ $budget->reliquat < 0 ? 'text-rose-400' : 'text-emerald-400' }} tracking-tight">
                                    {{ number_format($budget->reliquat, 0, ',', ' ') }}
                                </span>
                                <span class="text-lg font-bold text-white/40">DH</span>
                            </div>
                            <p class="text-white/40 text-[10px] font-bold uppercase tracking-wider">Disponible pour allocation</p>
                        </div>
                    </div>
                </div>

                {{-- Fast Actions / Status --}}
                <div class="bg-surface-container-lowest rounded-[48px] p-10 flex flex-col justify-center items-center border-0 shadow-sm text-center">
                    <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="font-display font-bold text-slate-800 text-lg mb-2">Budget Actif</h4>
                    <p class="text-slate-400 text-xs font-medium px-4 leading-relaxed">Ce budget est actuellement ouvert pour les propositions d'engagement.</p>
                </div>
            </div>

            {{-- Emetteurs Table Section --}}
            <div class="bg-surface-container-low rounded-[48px] overflow-hidden border-0">
                <div class="p-8 px-10 flex justify-between items-center bg-white/50 backdrop-blur-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-xl font-display font-bold text-slate-900 tracking-tight">Émetteurs rattachés</h3>
                    </div>
                    <span class="px-4 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $budget->emetteurs->count() }} Départements / Services</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-left">Émetteur</th>
                                <th class="px-6 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-right">Dotation Initiale</th>
                                <th class="px-6 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-right">Consommé</th>
                                <th class="px-10 py-5 text-[10px] uppercase font-bold text-slate-400 tracking-widest text-right">Reliquat Restant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @forelse($budget->emetteurs as $emetteur)
                                <tr class="group hover:bg-white transition-colors duration-200">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-display font-bold text-slate-400">
                                                {{ substr($emetteur->user->name, 0, 1) }}
                                            </div>
                                            <div class="font-display font-bold text-slate-900 text-base italic">{{ $emetteur->user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-right font-medium text-slate-600">
                                        {{ number_format($emetteur->dotation, 2, ',', ' ') }} <small class="opacity-50">DH</small>
                                    </td>
                                    <td class="px-6 py-6 text-right font-medium text-slate-600">
                                        {{ number_format($emetteur->montant_approuve, 2, ',', ' ') }} <small class="opacity-50">DH</small>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="text-lg font-display font-bold {{ $emetteur->reliquat < 1000 ? 'text-rose-500' : 'text-primary' }} group-hover:scale-110 transition-transform inline-block">
                                            {{ number_format($emetteur->reliquat, 2, ',', ' ') }} <small class="text-[10px] opacity-60 uppercase tracking-widest ml-1">DH</small>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-10 py-20 text-center">
                                        <div class="flex flex-col items-center space-y-4">
                                            <div class="p-4 bg-slate-50 rounded-full">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            </div>
                                            <p class="text-slate-400 font-medium italic">Aucun émetteur rattaché à ce budget.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Navigation Action --}}
            <div class="flex items-center justify-center pt-8">
                <a href="{{ route('admin.budgets.index') }}" class="group flex items-center space-x-2 text-slate-400 hover:text-primary transition-colors duration-300 font-bold tracking-widest text-[10px] uppercase">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Retour à la liste des budgets</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
