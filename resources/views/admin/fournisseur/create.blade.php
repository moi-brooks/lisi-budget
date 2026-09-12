<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un Fournisseur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.fournisseurs.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700 text-sm font-bold mb-2">Nom :</label>
                        <input type="text" name="nom" id="nom" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('nom') }}" required autofocus>
                        @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="adresse" class="block text-gray-700 text-sm font-bold mb-2">Adresse (Optionnel) :</label>
                        <input type="text" name="adresse" id="adresse" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('adresse') }}">
                        @error('adresse') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('admin.fournisseurs.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Créer le fournisseur
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
