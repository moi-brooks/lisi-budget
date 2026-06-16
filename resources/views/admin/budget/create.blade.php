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
                        <label for="annee" class="block text-gray-700 text-sm font-bold mb-2">Année budgétaire :</label>
                        <input type="text" inputmode="numeric" name="annee" id="annee"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               value="{{ old('annee', date('Y')) }}" placeholder="ex: 2025" required>
                        @error('annee') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Dotation annuelle (DH) :</label>
                        <input type="text" inputmode="decimal" name="total" id="total"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               value="{{ old('total') }}" placeholder="ex: 2500000" required>
                        @error('total') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="date_limite" class="block text-gray-700 text-sm font-bold mb-2">Date Limite de Soumission (Optionnel) :</label>
                        <input type="date" name="date_limite" id="date_limite"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               value="{{ old('date_limite') }}">
                        <p class="text-gray-500 text-xs mt-1">Au-delà de cette date, les émetteurs ne pourront plus soumettre de propositions.</p>
                        @error('date_limite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
