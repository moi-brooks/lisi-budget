@php $editing = isset($budget); @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $editing ? 'Modifier le budget' : 'Nouveau budget' }}</h2>
    </x-slot>
    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow p-6">
            <form method="POST" action="{{ $editing ? route('budgets.update', $budget) : route('budgets.store') }}">
                @csrf
                @if($editing) @method('PATCH') @endif

                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Année</label>
                            <input type="number" name="annee" value="{{ old('annee', $budget->annee ?? date('Y')) }}"
                                class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('annee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <select name="statut" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach(['brouillon', 'actif', 'clos'] as $s)
                                <option value="{{ $s }}" {{ old('statut', $budget->statut ?? 'brouillon') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="titre" value="{{ old('titre', $budget->titre ?? '') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total prévisionnel (DA)</label>
                        <input type="number" step="0.01" name="total_previsionnel" value="{{ old('total_previsionnel', $budget->total_previsionnel ?? '') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('total_previsionnel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $budget->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    <a href="{{ route('budgets.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Annuler</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                        {{ $editing ? 'Mettre à jour' : 'Créer le budget' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
