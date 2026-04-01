<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier la Ligne — {{ $ligne->nom }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-8">

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-3 mb-6 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.budgets.lignes.update', [$budget, $ligne]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500">Article</label>
                            <input type="number" name="article" value="{{ old('article', $ligne->article) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500">Paragraphe</label>
                            <input type="number" name="paragraphe" value="{{ old('paragraphe', $ligne->paragraphe) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500">Rubrique</label>
                            <input type="number" name="rubrique" value="{{ old('rubrique', $ligne->rubrique) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500">Code Ligne</label>
                        <input type="text" name="code_ligne" value="{{ old('code_ligne', $ligne->code_ligne) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500">Nom de la Rubrique</label>
                        <input type="text" name="nom" value="{{ old('nom', $ligne->nom) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow transition">
                            Enregistrer
                        </button>
                        <a href="{{ route('admin.budgets.lignes.index', $budget) }}"
                           class="text-sm text-slate-500 hover:text-slate-700 font-medium">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
