<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex items-end justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Modifier l'Expression de Besoins</h1>
                <p class="mt-2 text-slate-500 font-medium italic">Ajustez les détails de votre proposition.</p>
            </div>
            
            @php
                $reliquatSansLigne = auth()->user()->emetteur->dotation - auth()->user()->emetteur->lignesProposees()
                    ->where('id', '!=', $ligne->id)
                    ->whereIn('statut', ['en_attente', 'approuve'])
                    ->sum('montant');
            @endphp

            <div class="bg-indigo-50 px-6 py-3 rounded-2xl border border-indigo-100/50 text-right">
                <span class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Capacité maximale</span>
                <span class="text-xl font-black text-indigo-600 tracking-tight">{{ number_format($reliquatSansLigne, 2, ',', ' ') }} <span class="text-sm">DH</span></span>
            </div>
        </div>

        @if($ligne->statut === 'rejete')
            <div class="mb-8 bg-white border border-red-100 rounded-3xl p-6 shadow-xl shadow-red-900/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:rotate-12 transition-transform">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-black text-red-600 uppercase tracking-widest text-[11px] mb-2">Motif du rejet précédent</h3>
                <p class="text-slate-700 italic font-medium leading-relaxed">{{ $ligne->motif_refus }}</p>
                <p class="mt-4 text-[11px] text-slate-400 font-bold uppercase tracking-widest">Une nouvelle soumission réinitialisera l'attente d'approbation</p>
            </div>
        @endif

        <form action="{{ route('emetteur.lignes.update', $ligne) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white shadow-2xl shadow-slate-200/60 rounded-[2.5rem] border border-slate-100 p-10 space-y-8">
                
                {{-- Ligne --}}
                <div class="space-y-3">
                    <label for="ligne_budgetaire_id" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">Catégorie Budgétaire</label>
                    <select name="ligne_budgetaire_id" id="ligne_budgetaire_id" required
                        class="block w-full rounded-2xl border-slate-200 bg-slate-50 text-[15px] font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-4 shadow-sm">
                        @foreach($lignes as $l)
                            <option value="{{ $l->id }}" {{ old('ligne_budgetaire_id', $ligne->ligne_budgetaire_id) == $l->id ? 'selected' : '' }}>
                                {{ $l->code_complet }} - {{ $l->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="space-y-3">
                    <label for="description" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">Description détaillée</label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Précisez votre besoin (ex: Matériel informatique, consommables...)"
                        class="block w-full rounded-2xl border-slate-200 bg-slate-50 text-[15px] text-slate-600 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-4 shadow-sm resize-none">{{ old('description', $ligne->description) }}</textarea>
                </div>

                {{-- Montant --}}
                <div class="space-y-3">
                    <label for="montant" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">Montant souhaité</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="montant" id="montant" value="{{ old('montant', $ligne->montant) }}" required
                            class="block w-full rounded-[1.5rem] border-slate-200 bg-slate-50 text-[18px] font-black text-indigo-600 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-5 shadow-sm"
                            placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400 font-black text-xs uppercase tracking-widest">
                            MAD
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('montant')" />
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 px-4">
                <a href="{{ route('emetteur.lignes.index') }}" class="text-xs font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-[0.2em]">
                    Abandonner
                </a>
                <button type="submit"
                    class="group inline-flex items-center gap-4 bg-indigo-600 text-white font-black px-12 py-5 rounded-[2rem] shadow-2xl shadow-indigo-200 hover:bg-indigo-700 transition-all hover:-translate-y-1 uppercase tracking-[0.15em] text-xs">
                    Mettre à jour
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
