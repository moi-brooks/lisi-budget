<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Initialiser un Bon de Commande') }}
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

            @if($lignesApprouvees->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <p class="text-yellow-700 font-bold">Vous n'avez aucune ligne budgétaire approuvée.</p>
                    <p class="text-sm mt-1">Vous devez d'abord obtenir l'approbation d'une proposition budgétaire par l'administration avant de pouvoir saisir un bon de commande.</p>
                </div>
            @else

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('emetteur.engagements.store') }}" method="POST">
                    @csrf
                    
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Informations Générales</h3>

                    <div class="mb-4">
                        <label for="ligne_proposee_id" class="block text-gray-700 text-sm font-bold mb-2">Imputer sur la ligne :</label>
                        <select name="ligne_proposee_id" id="ligne_proposee_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Sélectionner une ligne approuvée --</option>
                            @foreach($lignesApprouvees as $ligne_prop)
                                <option value="{{ $ligne_prop->id }}" {{ old('ligne_proposee_id', request('ligne_id')) == $ligne_prop->id ? 'selected' : '' }}>
                                    {{ $ligne_prop->ligne->code_complet }} - {{ $ligne_prop->ligne->nom }} (Dispo: {{ number_format($ligne_prop->montant, 2) }} DH)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fournisseur_id" class="block text-gray-700 text-sm font-bold mb-2">Fournisseur :</label>
                            <select name="fournisseur_id" id="fournisseur_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">-- Non défini / En attente --</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                        {{ $fournisseur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date de la commande :</label>
                            <input type="date" name="date" id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label for="commentaire" class="block text-gray-700 text-sm font-bold mb-2">Commentaire :</label>
                            <input type="text" name="commentaire" id="commentaire" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('commentaire') }}" placeholder="Optionnel...">
                        </div>
                        <div>
                            <label for="tva" class="block text-gray-700 text-sm font-bold mb-2">TVA Globale (%) :</label>
                            <input type="number" step="0.01" name="tva" id="tva" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('tva', 20) }}" required>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Articles (Besoins)</h3>
                    <p class="text-xs text-gray-500 mb-4">Vous pourrez ajouter d'autres articles ou modifier les statuts de livraison plus tard, mais il faut saisir au moins un article initialemen                    <div id="articles-container">
                        <div class="flex space-x-2 mb-2 article-row border-b pb-2">
                            <div class="flex-grow">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Intitulé</label>
                                <input type="text" name="intitule[]" placeholder="Ex: Ramettes papier" class="shadow-sm border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="flex-grow">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Description</label>
                                <input type="text" name="description[]" placeholder="Détails..." class="shadow-sm border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700">
                            </div>
                            <div class="w-24">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Qté</label>
                                <input type="number" name="quantite[]" step="1" value="1" min="1" class="shadow-sm border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700 qte-input" required>
                            </div>
                            <div class="w-32">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">PU HT</label>
                                <input type="number" step="0.01" name="prix_unitaire[]" placeholder="0.00" class="shadow-sm border-gray-300 rounded w-full py-2 px-3 text-sm text-gray-700 pu-input" required>
                            </div>
                            <div class="w-8 flex items-end justify-center pb-2">
                                <button type="button" class="text-red-500 font-bold hover:text-red-700 hidden del-btn" title="Supprimer">&times;</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mb-10 bg-gray-50 p-4 rounded-lg border-dashed border-2 border-gray-200">
                        <button type="button" id="add-article-btn" class="text-indigo-600 text-sm font-bold hover:underline flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Ajouter un article
                        </button>
                        <div class="text-right">
                            <span class="text-gray-500 text-xs font-bold uppercase block">Estimation Total HT</span>
                            <span id="total-ht-display" class="text-2xl font-black text-gray-900">0,00 DH</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 border-t pt-6">
                        <a href="{{ route('emetteur.engagements.index') }}" class="text-gray-500 hover:text-gray-800 font-medium transition">Annuler</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-200 uppercase tracking-widest text-xs">
                            Soumettre le bon de commande
                        </button>
                    </div>
                </form>

            </div>
            
            @push('scripts')
            <script>
                function calculateTotalHT() {
                    let total = 0;
                    const rows = document.querySelectorAll('.article-row');
                    rows.forEach(row => {
                        const qte = parseFloat(row.querySelector('input[name="quantite[]"]').value) || 0;
                        const pu = parseFloat(row.querySelector('input[name="prix_unitaire[]"]').value) || 0;
                        total += qte * pu;
                    });
                    document.getElementById('total-ht-display').innerText = new Intl.NumberFormat('fr-FR', { 
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(total) + ' DH';
                }

                document.getElementById('articles-container').addEventListener('input', calculateTotalHT);

                document.getElementById('add-article-btn').addEventListener('click', function() {
                    const container = document.getElementById('articles-container');
                    const firstRow = container.querySelector('.article-row');
                    const row = firstRow.cloneNode(true);
                    
                    // Clear inputs in cloned row
                    row.querySelectorAll('input').forEach(input => input.value = '');
                    row.querySelector('input[name="quantite[]"]').value = '1';
                    
                    // Show delete button
                    const delBtn = row.querySelector('.del-btn');
                    delBtn.classList.remove('hidden');
                    delBtn.addEventListener('click', function() {
                        row.remove();
                        calculateTotalHT();
                    });
                    
                    container.appendChild(row);
                    calculateTotalHT();
                });
                
                // Initial calculation
                calculateTotalHT();
            </script>
            @endpush

            @endif
        </div>
    </div>
</x-app-layout>
