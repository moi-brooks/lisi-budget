<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier une Proposition Budgétaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($ligne->statut === 'rejete')
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                    <h3 class="font-bold text-red-800">Motif du précédent rejet :</h3>
                    <p class="text-red-700 italic mt-1">{{ $ligne->motif_refus }}</p>
                    <p class="text-sm text-gray-600 mt-2">En modifiant et soumettant à nouveau cette proposition, elle repassera en attente d'approbation d'administration.</p>
                </div>
            @endif
            
            @php
                // Reliquat hors de la proposition actuelle
                $reliquatSansLigne = auth()->user()->emetteur->dotation - auth()->user()->emetteur->lignesProposees()
                    ->where('id', '!=', $ligne->id)
                    ->whereIn('statut', ['en_attente', 'approuve'])
                    ->sum('montant');
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-gray-500 text-sm font-semibold uppercase">Votre Reliquat Maximal Théorique</h3>
                        <p class="text-sm text-gray-500 mt-1">Si vous annulez cette proposition, voici ce qui serait disponible.</p>
                    </div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($reliquatSansLigne, 2, ',', ' ') }} DH</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('emetteur.lignes.update', $ligne) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="ligne_budgetaire_id" class="block text-gray-700 text-sm font-bold mb-2">Ligne Budgétaire cible :</label>
                        <select name="ligne_budgetaire_id" id="ligne_budgetaire_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @foreach($lignes as $l)
                                <option value="{{ $l->id }}" {{ old('ligne_budgetaire_id', $ligne->ligne_budgetaire_id) == $l->id ? 'selected' : '' }}>
                                    {{ $l->code_complet }} - {{ $l->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="montant" class="block text-gray-700 text-sm font-bold mb-2">Montant proposé (DH) :</label>
                        <input type="number" step="0.01" name="montant" id="montant" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('montant', $ligne->montant) }}" required max="{{ $reliquatSansLigne }}">
                    </div>



                    <div class="flex items-center justify-end">
                        <a href="{{ route('emetteur.lignes.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Mettre à jour la proposition
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
