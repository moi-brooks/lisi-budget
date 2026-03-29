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
                        <label for="ligne_budget_proposee_id" class="block text-gray-700 text-sm font-bold mb-2">Imputer sur la ligne :</label>
                        <select name="ligne_budget_proposee_id" id="ligne_budget_proposee_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Sélectionner une ligne approuvée --</option>
                            @foreach($lignesApprouvees as $ligne_prop)
                                <option value="{{ $ligne_prop->id }}" {{ old('ligne_budget_proposee_id', request('ligne_id')) == $ligne_prop->id ? 'selected' : '' }}>
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
                    <p class="text-xs text-gray-500 mb-4">Vous pourrez ajouter d'autres articles ou modifier les statuts de livraison plus tard, mais il faut saisir au moins un article initialement.</p>

                    <div id="articles-container">
                        <div class="flex space-x-2 mb-2 article-row">
                            <div class="flex-grow">
                                <input type="text" name="intitule[]" placeholder="Intitulé" class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="flex-grow">
                                <input type="text" name="description[]" placeholder="Description (optionnel)" class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700">
                            </div>
                            <div class="w-24">
                                <input type="number" name="quantite[]" placeholder="Qté" value="1" min="1" class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-32">
                                <input type="number" step="0.01" name="prix_unitaire[]" placeholder="Prix UT HT" class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-10 flex items-center justify-center">
                                <button type="button" class="text-red-500 font-bold hover:text-red-700 hidden del-btn">&times;</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <button type="button" id="add-article-btn" class="text-blue-600 text-sm font-bold hover:underline">+ Ajouter un autre article</button>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('emetteur.engagements.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Soumettre le bon de commande
                        </button>
                    </div>
                </form>

            </div>
            
            @push('scripts')
            <script>
                document.getElementById('add-article-btn').addEventListener('click', function() {
                    const container = document.getElementById('articles-container');
                    const row = container.querySelector('.article-row').cloneNode(true);
                    
                    // Clear inputs in cloned row
                    row.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                    row.querySelector('input[name="quantite[]"]').value = '1';
                    row.querySelector('input[name="prix_unitaire[]"]').value = '';

                    
                    // Show delete button
                    const delBtn = row.querySelector('.del-btn');
                    delBtn.classList.remove('hidden');
                    delBtn.addEventListener('click', function() {
                        row.remove();
                    });
                    
                    container.appendChild(row);
                });
            </script>
            @endpush

            @endif
        </div>
    </div>
</x-app-layout>
