<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nouvel engagement</h2>
    </x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow p-6">
            <form method="POST" action="{{ route('engagements.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ligne budgétaire</label>
                        <select name="ligne_budgetaire_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">— Choisir —</option>
                            @foreach($lignes as $ligne)
                            <option value="{{ $ligne->id }}" {{ old('ligne_budgetaire_id') == $ligne->id ? 'selected' : '' }}>
                                [{{ $ligne->code }}] {{ $ligne->libelle }} — {{ $ligne->budget->titre }}
                            </option>
                            @endforeach
                        </select>
                        @error('ligne_budgetaire_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Émetteur</label>
                        <select name="emetteur_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">— Choisir —</option>
                            @foreach($emetteurs as $e)
                            <option value="{{ $e->id }}" {{ old('emetteur_id', auth()->user()->emetteur?->id) == $e->id ? 'selected' : '' }}>
                                {{ $e->nom }}
                            </option>
                            @endforeach
                        </select>
                        @error('emetteur_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Montant (DA)</label>
                            <input type="number" step="0.01" name="montant" value="{{ old('montant') }}"
                                class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('montant') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date d'engagement</label>
                            <input type="date" name="date_engagement" value="{{ old('date_engagement', now()->format('Y-m-d')) }}"
                                class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('date_engagement') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <a href="{{ route('engagements.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Annuler</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
