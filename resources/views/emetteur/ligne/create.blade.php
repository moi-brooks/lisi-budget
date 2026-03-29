<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proposer une Répartition Budgétaire') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-gray-500 text-sm font-semibold uppercase">Votre Reliquat Disponible</h3>
                        <p class="text-sm text-gray-500 mt-1">Ceci est le montant maximum que vous pouvez proposer aujourd'hui.</p>
                    </div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format(auth()->user()->emetteur->reliquat, 2, ',', ' ') }} DH</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('emetteur.lignes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="ligne_budgetaire_id" class="block text-gray-700 text-sm font-bold mb-2">Ligne Budgétaire cible :</label>
                        <select name="ligne_budgetaire_id" id="ligne_budgetaire_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Sélectionner une ligne --</option>
                            @foreach($lignes as $ligne)
                                <option value="{{ $ligne->id }}" {{ old('ligne_budgetaire_id') == $ligne->id ? 'selected' : '' }}>
                                    {{ $ligne->code_complet }} - {{ $ligne->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="montant" class="block text-gray-700 text-sm font-bold mb-2">Montant proposé (DH) :</label>
                        <input type="number" step="0.01" name="montant" id="montant" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('montant') }}" required max="{{ auth()->user()->emetteur->reliquat }}">
                    </div>



                    <div class="flex items-center justify-end">
                        <a href="{{ route('emetteur.lignes.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Soumettre la proposition
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
