<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Initialiser une Expression de Besoins') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-600 border border-red-700 text-white px-4 py-3 rounded-lg relative shadow-lg font-bold">
                    {{ session('error') }}
                </div>
            @endif

            @if($lignesApprouvees->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <p class="text-yellow-700 font-bold">Vous n'avez aucune ligne budgétaire approuvée.</p>
                    <p class="text-sm mt-1">Vous devez d'abord obtenir l'approbation d'une proposition budgétaire par l'administration avant de pouvoir saisir une expression de besoins.</p>
                </div>
            @else

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                 x-data="{
                    ligneId: '{{ old('ligne_proposee_id', request('ligne_id', '')) }}',
                    lignesData: {{ json_encode($lignesApprouvees->map(fn($l) => ['id' => $l->id, 'montant' => (float)$l->montant_disponible])->keyBy('id')) }},
                    articles: [{ intitule: '', description: '', quantite: '1', prix_unitaire: '' }],
                    tva: '{{ old('tva', 20) }}',
                    get totalHT() {
                        return this.articles.reduce((s, a) => {
                            const q = parseFloat(a.quantite) || 0;
                            const p = parseFloat(a.prix_unitaire) || 0;
                            return s + q * p;
                        }, 0);
                    },
                    get totalTTC() {
                        const t = parseFloat(this.tva) || 0;
                        return this.totalHT * (1 + t / 100);
                    },
                    get montantDispo() {
                        if (!this.ligneId) return null;
                        const l = this.lignesData[this.ligneId];
                        return l ? parseFloat(l.montant) : null;
                    },
                    get depasse() {
                        if (this.montantDispo === null || this.totalTTC === 0) return false;
                        return this.totalTTC > this.montantDispo;
                    },
                    addArticle() {
                        this.articles.push({ intitule: '', description: '', quantite: '1', prix_unitaire: '' });
                    },
                    removeArticle(i) {
                        if (this.articles.length > 1) this.articles.splice(i, 1);
                    },
                    formatNum(n) {
                        return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
                    }
                 }">

                <form action="{{ route('emetteur.engagements.store') }}" method="POST"
                      @submit="if(depasse) { $event.preventDefault(); }">
                    @csrf

                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Informations Générales</h3>

                    <div class="mb-4">
                        <label for="ligne_proposee_id" class="block text-gray-700 text-sm font-bold mb-2">Imputer sur la ligne :</label>
                        <select name="ligne_proposee_id" id="ligne_proposee_id" x-model="ligneId"
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Sélectionner une ligne approuvée --</option>
                            @foreach($lignesApprouvees as $ligne_prop)
                                <option value="{{ $ligne_prop->id }}" {{ old('ligne_proposee_id', request('ligne_id')) == $ligne_prop->id ? 'selected' : '' }}>
                                    {{ $ligne_prop->ligne->code_complet }} — {{ $ligne_prop->ligne->nom }}
                                    (Restant : {{ number_format($ligne_prop->montant_disponible, 2, ',', ' ') }} DH)
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Tous les articles doivent relever de la même ligne budgétaire sélectionnée.</p>
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fournisseur_id" class="block text-gray-700 text-sm font-bold mb-2">Fournisseur :</label>
                            @if($fournisseurs->isEmpty())
                                <div class="bg-amber-50 border border-amber-300 text-amber-800 rounded-lg px-4 py-3 text-sm">
                                    Aucun fournisseur disponible. Veuillez contacter l'administrateur.
                                </div>
                                <input type="hidden" name="fournisseur_id" value="">
                            @else
                                <select name="fournisseur_id" id="fournisseur_id"
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">-- Non défini / En attente --</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                            {{ $fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div>
                            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date de la commande :</label>
                            <input type="date" name="date" id="date"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label for="commentaire" class="block text-gray-700 text-sm font-bold mb-2">Commentaire :</label>
                            <input type="text" name="commentaire" id="commentaire"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('commentaire') }}" placeholder="Optionnel...">
                        </div>
                        <div>
                            <label for="tva" class="block text-gray-700 text-sm font-bold mb-2">TVA Globale (%) :</label>
                            <input type="text" inputmode="decimal" name="tva" id="tva" x-model="tva"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('tva', 20) }}" required>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold mb-2 border-b pb-2">Articles (Besoins)</h3>
                    <p class="text-xs text-gray-500 mb-4">Saisir au moins un article. Tous les articles doivent relever de la même ligne budgétaire.</p>

                    <template x-for="(article, index) in articles" :key="index">
                        <div class="flex space-x-2 mb-2 border-b pb-2">
                            <div class="flex-grow">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Intitulé</label>
                                <input type="text" :name="'intitule[]'" x-model="article.intitule"
                                       placeholder="Ex: Ramettes papier"
                                       class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="flex-grow">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Description</label>
                                <input type="text" :name="'description[]'" x-model="article.description"
                                       placeholder="Détails..."
                                       class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700">
                            </div>
                            <div class="w-24">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Qté</label>
                                <input type="text" inputmode="numeric" :name="'quantite[]'" x-model="article.quantite"
                                       class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-32">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">PU HT (DH)</label>
                                <input type="text" inputmode="decimal" :name="'prix_unitaire[]'" x-model="article.prix_unitaire"
                                       placeholder="0.00"
                                       class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-8 flex items-end justify-center pb-2">
                                <button type="button" @click="removeArticle(index)"
                                        x-show="articles.length > 1"
                                        class="text-red-500 font-bold hover:text-red-700 text-xl leading-none">&times;</button>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-between items-center mb-6 bg-gray-50 p-4 rounded-lg border-dashed border-2 border-gray-200">
                        <button type="button" @click="addArticle"
                                class="text-indigo-600 text-sm font-bold hover:underline flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter un article
                        </button>
                        <div class="text-right">
                            <span class="text-gray-500 text-xs font-bold uppercase block">Estimation Total HT</span>
                            <span class="text-2xl font-black text-gray-900" x-text="formatNum(totalHT) + ' DH'"></span>
                            <span class="text-xs text-slate-500 block" x-text="'TTC : ' + formatNum(totalTTC) + ' DH'"></span>
                        </div>
                    </div>

                    <div x-show="depasse" x-transition
                         class="mb-4 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-lg font-semibold text-sm">
                        Le montant de l'engagement dépasse le montant alloué à cette ligne budgétaire.
                        <span class="block text-xs font-normal mt-1">
                            Disponible : <span class="font-bold" x-text="formatNum(montantDispo) + ' DH'"></span>
                            — TTC calculé : <span class="font-bold" x-text="formatNum(totalTTC) + ' DH'"></span>
                        </span>
                    </div>

                    <div class="flex items-center justify-end space-x-4 border-t pt-6">
                        <a href="{{ route('emetteur.engagements.index') }}" class="text-gray-500 hover:text-gray-800 font-medium transition">Annuler</a>
                        <button type="submit"
                                :disabled="depasse"
                                :class="depasse ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                                class="text-white font-bold py-3 px-10 rounded-lg shadow-lg transform transition active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-200 uppercase tracking-widest text-sm">
                            ENGAGER
                        </button>
                    </div>
                </form>

            </div>
            @endif
        </div>
    </div>
</x-app-layout>
