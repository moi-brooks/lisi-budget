<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8" x-data="{ 
        rows: [{ id: Date.now(), ligne_id: '', description: '', amount: 0 }],
        maxRows: 10,
        addRow() {
            if (this.rows.length < this.maxRows) {
                this.rows.push({ id: Date.now(), ligne_id: '', description: '', amount: 0 });
            }
        },
        removeRow(index) {
            if (this.rows.length > 1) {
                this.rows.splice(index, 1);
            }
        },
        get totalAmount() {
            return this.rows.reduce((sum, row) => sum + (parseFloat(row.amount) || 0), 0);
        }
    }">
        <div class="mb-10 flex items-end justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Expressions de Besoins</h1>
                <p class="mt-2 text-slate-500 font-medium italic">Saisissez vos propositions pour l'exercice en cours.</p>
            </div>
            <div class="bg-indigo-50 px-6 py-3 rounded-2xl border border-indigo-100/50 text-right">
                <span class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Reliquat disponible</span>
                <span class="text-xl font-black text-indigo-600 tracking-tight">{{ number_format(auth()->user()->emetteur->reliquat, 2, ',', ' ') }} <span class="text-sm">DH</span></span>
            </div>
        </div>

        <form action="{{ route('emetteur.lignes.store') }}" method="POST" class="space-y-8">
            @csrf

            @if($errors->has('global'))
                <div class="p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm font-medium rounded-r-xl shadow-sm">
                    {{ $errors->first('global') }}
                </div>
            @endif

            <div class="bg-white shadow-2xl shadow-slate-200/60 rounded-[2.5rem] overflow-hidden border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Catégorie Budgétaire</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest min-w-[300px]">Description détaillée</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest w-48">Montant (DH)</th>
                            <th class="px-6 py-5 text-center w-20"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, index) in rows" :key="row.id">
                            <tr class="hover:bg-slate-50/40 transition-colors group">
                                <td class="px-8 py-6 align-top">
                                    <select :name="`propositions[${index}][ligne_budgetaire_id]`" required
                                        class="block w-full rounded-2xl border-slate-200 bg-slate-50 text-[13.5px] font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-3.5 shadow-sm">
                                        <option value="">Sélectionner...</option>
                                        @foreach($lignes as $l)
                                            <option value="{{ $l->id }}">{{ $l->nom }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-8 py-6 align-top">
                                    <textarea :name="`propositions[${index}][description]`" x-model="row.description" rows="1"
                                        placeholder="Précisez votre besoin (ex: MacBook Pro M3, 16Go RAM...)"
                                        class="block w-full rounded-2xl border-slate-200 bg-slate-50 text-[13.5px] text-slate-600 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-3.5 shadow-sm resize-none"></textarea>
                                </td>
                                <td class="px-8 py-6 align-top">
                                    <div class="relative">
                                        <input type="number" step="0.01" :name="`propositions[${index}][montant]`" x-model="row.amount" required
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-50 text-[15px] font-bold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 transition-all p-3.5 pr-4 text-right shadow-sm"
                                            placeholder="0.00">
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center align-top">
                                    <button type="button" @click="removeRow(index)" x-show="rows.length > 1"
                                        class="p-3 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all opacity-0 group-hover:opacity-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-slate-50/30">
                        <tr>
                            <td colspan="2" class="px-8 py-8">
                                <button type="button" @click="addRow()" x-show="rows.length < maxRows"
                                    class="inline-flex items-center gap-2 text-[11px] font-black text-indigo-500 hover:text-indigo-700 uppercase tracking-[0.2em] transition-all bg-white hover:bg-indigo-50 px-6 py-3 rounded-2xl border border-indigo-100 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Ajouter un besoin
                                </button>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Total Proposé</div>
                                <div class="text-2xl font-black text-indigo-600 tracking-tight" x-text="new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(totalAmount)"></div>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex items-center justify-between pt-6 px-4">
                <a href="{{ route('emetteur.lignes.index') }}" class="text-xs font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-[0.2em]">
                    Annuler l'opération
                </a>
                <button type="submit"
                    class="group inline-flex items-center gap-4 bg-indigo-600 text-white font-black px-12 py-5 rounded-[2rem] shadow-2xl shadow-indigo-200 hover:bg-indigo-700 transition-all hover:-translate-y-1 uppercase tracking-[0.15em] text-xs">
                    Soumettre les besoins
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
