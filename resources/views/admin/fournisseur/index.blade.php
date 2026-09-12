<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Gestion des Fournisseurs') }}
            </h2>
            <a href="{{ route('admin.fournisseurs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300 shadow-sm">
                Nouveau Fournisseur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                                <th class="p-4">Nom</th>
                                <th class="p-4">Adresse</th>
                                <th class="p-4 text-center">Engagements liés</th>
                                <th class="p-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fournisseurs as $fournisseur)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                    <td class="p-4 font-semibold text-slate-800">{{ $fournisseur->nom }}</td>
                                    <td class="p-4 text-slate-600">{{ $fournisseur->adresse ?? '-' }}</td>
                                    <td class="p-4 text-center text-slate-600">
                                        <span class="inline-flex items-center justify-center min-w-[1.75rem] h-7 px-2 rounded-full bg-slate-100 font-semibold text-xs">
                                            {{ $fournisseur->engagements_count }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('admin.fournisseurs.edit', $fournisseur) }}" class="inline-flex items-center justify-center bg-amber-50 text-amber-700 hover:bg-amber-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Modifier</a>
                                            <form action="{{ route('admin.fournisseurs.destroy', $fournisseur) }}" method="POST"
                                                  onsubmit="return confirm('Confirmer la suppression de {{ $fournisseur->nom }} ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        {{ $fournisseur->engagements_count > 0 ? 'disabled title="Fournisseur utilisé par des engagements — suppression impossible"' : '' }}
                                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-sm transition font-medium {{ $fournisseur->engagements_count > 0 ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-500 font-medium">Aucun fournisseur configuré.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
