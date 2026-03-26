@php $editing = isset($besoin); @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $editing ? 'Modifier le besoin' : 'Nouveau besoin' }}</h2>
    </x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow p-6">
            <form method="POST" action="{{ $editing ? route('besoins.update', $besoin) : route('besoins.store') }}">
                @csrf
                @if($editing) @method('PATCH') @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Émetteur</label>
                        <select name="emetteur_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            {{ $editing ? 'disabled' : '' }}>
                            @foreach($emetteurs as $e)
                            <option value="{{ $e->id }}" {{ old('emetteur_id', $besoin->emetteur_id ?? auth()->user()->emetteur?->id) == $e->id ? 'selected' : '' }}>
                                {{ $e->nom }}
                            </option>
                            @endforeach
                        </select>
                        @if($editing)
                            <input type="hidden" name="emetteur_id" value="{{ $besoin->emetteur_id }}">
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Libellé</label>
                        <input type="text" name="libelle" value="{{ old('libelle', $besoin->libelle ?? '') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('libelle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Montant estimé (DA)</label>
                        <input type="number" step="0.01" name="montant" value="{{ old('montant', $besoin->montant ?? '') }}"
                            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('montant') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priorité</label>
                        <select name="priorite" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['faible', 'moyenne', 'haute'] as $p)
                            <option value="{{ $p }}" {{ old('priorite', $besoin->priorite ?? 'moyenne') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($editing)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="statut" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['en_attente', 'approuve', 'rejete'] as $s)
                            <option value="{{ $s }}" {{ old('statut', $besoin->statut) === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    <a href="{{ route('besoins.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Annuler</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                        {{ $editing ? 'Mettre à jour' : 'Soumettre' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
