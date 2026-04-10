<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mes Propositions de Répartition</h1>
                <p class="mt-2 text-slate-500 font-medium">Répartissez votre dotation sur les lignes budgétaires.</p>
            </div>
            <a href="{{ route('emetteur.lignes.create') }}" 
                class="group inline-flex items-center gap-3 bg-indigo-600 text-white font-black px-8 py-4 rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all hover:-translate-y-1 uppercase tracking-[0.15em] text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Nouvelle Proposition
            </a>
        </div>

        {{-- Alerte Reliquat --}}
        @if(auth()->user()->emetteur->is_reliquat_faible)
            <div class="mb-8 bg-white border border-rose-100 rounded-3xl p-5 shadow-xl shadow-rose-900/5 flex items-center gap-4 animate-pulse">
                <div class="p-3 bg-rose-50 rounded-2xl text-rose-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-rose-600 uppercase tracking-widest leading-none mb-1">Attention : Reliquat Faible</h4>
                    <p class="text-xs text-slate-500 font-medium">Votre dotation est presque épuisée (moins de 10% restant).</p>
                </div>
            </div>
        @endif

        {{-- Tabs --}}
        <div class="mb-8 pb-1 flex items-center gap-2 overflow-x-auto no-scrollbar">
            @php
                $tabs = [
                    ['id' => 'en_attente', 'label' => 'En attente', 'color' => 'indigo'],
                    ['id' => 'approuve', 'label' => 'Approuvées', 'color' => 'emerald'],
                    ['id' => 'rejete', 'label' => 'Rejetées', 'color' => 'rose'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <a href="{{ route('emetteur.lignes.index', ['statut' => $tab['id']]) }}" 
                    class="whitespace-nowrap px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all border {{ $status === $tab['id'] ? "bg-{$tab['color']}-600 text-white border-{$tab['color']}-600 shadow-lg shadow-{$tab['color']}-100" : 'bg-white text-slate-400 border-slate-100 hover:border-slate-300 hover:text-slate-600' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        <div class="bg-white shadow-2xl shadow-slate-200/60 rounded-[2.5rem] overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Détails du besoin</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] w-48">Montant</th>
                            @if($status === 'rejete')
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Motif du rejet</th>
                            @endif
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($propositions as $prop)
                            <tr class="hover:bg-slate-50/40 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-widest">{{ $prop->ligne->code_complet }}</span>
                                            <span class="text-xs font-black text-slate-400">{{ $prop->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="text-[15px] font-bold text-slate-800 tracking-tight">{{ $prop->ligne->nom }}</div>
                                        @if($prop->description)
                                            <div class="text-sm text-slate-500 italic leading-relaxed max-w-xl">{{ $prop->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-lg font-black text-indigo-600 tracking-tight">
                                        {{ number_format($prop->montant, 2, ',', ' ') }} <span class="text-xs">DH</span>
                                    </div>
                                </td>
                                
                                @if($status === 'rejete')
                                    <td class="px-8 py-6">
                                        <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100/50 text-rose-700 text-xs font-medium leading-relaxed italic">
                                            {{ $prop->motif_refus }}
                                        </div>
                                    </td>
                                @endif
                                
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($status === 'en_attente' || $status === 'rejete')
                                            <a href="{{ route('emetteur.lignes.edit', $prop) }}" 
                                                class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Modifier">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('emetteur.lignes.destroy', $prop) }}" method="POST" onsubmit="return confirm('Annuler cette proposition ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Supprimer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @elseif($status === 'approuve')
                                            <a href="{{ route('emetteur.engagements.create', ['ligne_id' => $prop->id]) }}" 
                                                class="inline-flex items-center gap-2 bg-emerald-600 text-white font-black px-5 py-2.5 rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all text-[11px] uppercase tracking-widest">
                                                Initialiser EB
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $status === 'rejete' ? 4 : 3 }}" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="p-6 bg-slate-50 rounded-[2.5rem] text-slate-200">
                                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        </div>
                                        <p class="text-slate-400 font-bold text-sm tracking-tight">Aucune proposition {{ str_replace('_', ' ', $status) }} pour le moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
