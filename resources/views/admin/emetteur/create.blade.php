<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un Émetteur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.emetteurs.store') }}" method="POST">
                    @csrf
                    
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Informations Utilisateur</h3>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom complet :</label>
                        <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Adresse Email :</label>
                        <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('email') }}" required>
                        <span class="text-xs text-gray-500">Un mot de passe aléatoire sera généré. L'utilisateur pourra le réinitialiser.</span>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Informations Budgétaires</h3>

                    <div class="mb-4">
                        <label for="profession" class="block text-gray-700 text-sm font-bold mb-2">Profession / Titre (Optionnel) :</label>
                        <input type="text" name="profession" id="profession" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('profession') }}">
                        @error('profession') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="budget_id" class="block text-gray-700 text-sm font-bold mb-2">Budget Rattaché :</label>
                        <select name="budget_id" id="budget_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Sélectionner un budget --</option>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget->id }}" {{ old('budget_id') == $budget->id ? 'selected' : '' }}>
                                    Année {{ $budget->annee }} (Dispo: {{ number_format($budget->reliquat, 2) }} DH)
                                </option>
                            @endforeach
                        </select>
                        @error('budget_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="dotation" class="block text-gray-700 text-sm font-bold mb-2">Dotation Allouée (DH) :</label>
                        <input type="number" step="0.01" name="dotation" id="dotation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('dotation') }}" required>
                        @error('dotation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('admin.emetteurs.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Créer l'émetteur
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
