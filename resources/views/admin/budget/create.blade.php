<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau Budget') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.budgets.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="annee" class="block text-gray-700 text-sm font-bold mb-2">Année :</label>
                        <input type="number" name="annee" id="annee" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('annee', date('Y')) }}" required>
                        @error('annee') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="saison" class="block text-gray-700 text-sm font-bold mb-2">Saison :</label>
                        <input type="text" name="saison" id="saison" placeholder="ex: 2024-2025" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('saison') }}" required>
                        @error('saison') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Total Alloué (DH) :</label>
                        <input type="number" step="0.01" name="total" id="total" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('total') }}" required>
                        @error('total') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('admin.budgets.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Enregistrer le budget
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
