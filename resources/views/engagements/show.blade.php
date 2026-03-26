<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Engagement — {{ $engagement->description }}</h2>
    </x-slot>
    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-xl shadow p-6 space-y-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Émetteur</dt>
                    <dd class="mt-1 font-semibold text-gray-800">{{ $engagement->emetteur->nom }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Ligne budgétaire</dt>
                    <dd class="mt-1 font-mono text-gray-700">{{ $engagement->ligne->code }} — {{ $engagement->ligne->libelle }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Budget</dt>
                    <dd class="mt-1 text-gray-700">{{ $engagement->ligne->budget->titre }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Date</dt>
                    <dd class="mt-1 text-gray-700">{{ $engagement->date_engagement->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Montant</dt>
                    <dd class="mt-1 font-bold text-lg text-gray-800">{{ number_format($engagement->montant, 0, ',', ' ') }} DA</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 uppercase">Statut actuel</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 rounded-full text-sm {{ $engagement->statut === 'solde' ? 'bg-green-100 text-green-700' : ($engagement->statut === 'annule' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-700') }}">
                            {{ str_replace('_', ' ', ucfirst($engagement->statut)) }}
                        </span>
                    </dd>
                </div>
            </dl>

            @if(auth()->user()->isAdmin() || auth()->user()->isGestionnaire())
            <form method="POST" action="{{ route('engagements.update', $engagement) }}" class="border-t pt-5">
                @csrf @method('PATCH')
                <label class="block text-sm font-medium text-gray-700 mb-2">Changer le statut</label>
                <div class="flex gap-3 items-center">
                    <select name="statut" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['en_cours', 'solde', 'annule'] as $s)
                        <option value="{{ $s }}" {{ $engagement->statut === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">Mettre à jour</button>
                </div>
            </form>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('engagements.index') }}" class="text-sm text-indigo-600 hover:underline">← Retour aux engagements</a>
            </div>
        </div>
    </div>
</x-app-layout>
