<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Détail Expression de Besoins') }} <span class="text-slate-400 font-normal">#{{ str_pad($engagement->id, 5, '0', STR_PAD_LEFT) }}</span>
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.engagements.index') }}" class="text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                    Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- 1. HEADER SECTION: Status & Objective --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800">{{ $engagement->objet }}</h3>
                        <p class="text-slate-500 text-sm mt-1">
                            Par <span class="font-bold text-slate-700">{{ $engagement->emetteur?->user?->name ?? 'Inconnu' }}</span> 
                            le {{ $engagement->created_at?->format('d/m/Y') ?? 'N/A' }}
                        </p>
                    </div>
                    <x-status-badge :status="$engagement->statut" class="px-3 py-1" />
                </div>

                @if($engagement->statut === 'rejete')
                    <div class="bg-rose-50 border border-rose-100 p-4 rounded-lg mb-6">
                        <span class="text-xs font-bold text-rose-500 uppercase tracking-wider block mb-1">Motif du rejet</span>
                        <p class="text-sm text-rose-800 italic">{{ $engagement->motif_refus }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-100 pt-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Fournisseur suggéré</label>
                        <p class="font-bold text-slate-700">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Ligne Budgétaire</label>
                        <div class="flex items-start gap-3">
                            <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded border border-slate-200 text-slate-500 whitespace-nowrap mt-0.5">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                            <span class="text-sm font-semibold text-slate-700 leading-snug">{{ $engagement->ligneProposee?->ligne?->nom ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($engagement->ligneProposee?->description)
                    <div class="mt-6 p-4 bg-slate-50 rounded-lg border border-slate-100">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Détails de la proposition</label>
                        <p class="text-sm text-slate-600 leading-relaxed italic">"{{ $engagement->ligneProposee->description }}"</p>
                    </div>
                @endif
            </div>

            {{-- 2. ACTIONS SECTION: Only if Pending --}}
            @if($engagement->statut === 'en_attente')
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-indigo-900 text-lg">Action requise</h4>
                        <p class="text-sm text-indigo-700">Veuillez valider ou rejeter cette expression de besoins.</p>
                    </div>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <form action="{{ route('admin.engagements.approve', $engagement->id) }}" method="POST" class="flex-1 md:flex-none">
                            @csrf
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-sm" onclick="return confirm('Approuver cette demande ?');">
                                Approuver
                            </button>
                        </form>
                        <button @click="$dispatch('open-modal-reject', { id: '{{ $engagement->id }}' })" class="flex-1 md:flex-none bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold py-2.5 px-6 rounded-lg transition">
                            Rejeter
                        </button>
                    </div>
                    <x-modal-reject :id="$engagement->id" :route="route('admin.engagements.reject', $engagement->id)" title="Rejeter l'expression de besoins" />
                </div>
            @endif

            {{-- 3. ITEMS TABLE & TOTALS --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Articles demandés</h3>
                    <a href="{{ route('admin.engagements.download', $engagement->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Télécharger PDF
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-widest text-slate-400 font-bold border-b border-slate-100 bg-slate-50/10">
                                <th class="px-6 py-3">Désignation</th>
                                <th class="px-4 py-3 text-center">Qté</th>
                                <th class="px-6 py-3 text-right">Montant (DH)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($engagement->besoins as $besoin)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 leading-tight">{{ $besoin->intitule }}</div>
                                        @if($besoin->description)
                                            <div class="text-[11px] text-slate-400 mt-1 italic">{{ $besoin->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm font-medium text-slate-600">
                                        {{ $besoin->quantite }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono text-sm text-slate-800">
                                        {{ number_format($besoin->montant, 2, ',', ' ') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER / TOTALS --}}
                <div class="bg-slate-50 p-6 border-t border-slate-200">
                    <div class="max-w-xs ml-auto space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Total Hors Taxe</span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($engagement->total_ht, 2, ',', ' ') }} <small class="text-[10px] text-slate-400">DH</small></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">TVA ({{ (int)$engagement->tva }}%)</span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($engagement->total_ttc - $engagement->total_ht, 2, ',', ' ') }} <small class="text-[10px] text-slate-400">DH</small></span>
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex justify-between items-end">
                            <span class="font-bold text-slate-800">Total TTC</span>
                            <div class="text-right">
                                <div class="text-2xl font-black text-indigo-600 leading-none">
                                    {{ number_format($engagement->total_ttc, 2, ',', ' ') }}
                                </div>
                                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">DIRHAMS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. FOOTER NOTE --}}
            <div class="pt-8 text-center text-[10px] text-slate-400 font-medium uppercase tracking-widest">
                Généré par E-Intendance LISI • {{ now()->format('Y') }}
            </div>
        </div>
    </div>
</x-app-layout>


