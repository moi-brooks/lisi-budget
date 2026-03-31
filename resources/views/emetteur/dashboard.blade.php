<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Espace (Émetteur)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 mb-8 border border-slate-100">
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Bienvenue {{ $emetteur->user->name }}</h3>
                <p class="text-slate-500">Saison budgétaire active : <strong class="text-slate-800">{{ $emetteur->budget->saison }}</strong></p>
            </div>

            <!-- Budget Gauge -->
            @php
                $engagedTotal = $stats['montant_approuve'] + $stats['montant_attente'];
                $percent = $stats['dotation'] > 0 ? ($engagedTotal / $stats['dotation']) * 100 : 0;
                $percent = min($percent, 100); // Caps at 100% visually
                $color = $percent < 70 ? 'bg-green-500' : ($percent < 90 ? 'bg-orange-500' : 'bg-red-500');
            @endphp
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 mb-8 border border-slate-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Utilisation du Budget (Total Engagé)</h3>
                    <span class="text-sm font-bold {{ str_replace('bg-', 'text-', $color) }}">{{ number_format($percent, 1) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="{{ $color }} h-3 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-500 mt-4 font-medium uppercase tracking-wider">
                    <span>Dotation : <span class="text-slate-800 font-bold">{{ number_format($stats['dotation'], 2, ',', ' ') }} DH</span></span>
                    <span>Déjà Engagé : <span class="text-slate-800 font-bold">{{ number_format($engagedTotal, 2, ',', ' ') }} DH</span></span>
                    <span>Reliquat Réel : <span class="text-slate-800 font-bold">{{ number_format($stats['reliquat'], 2, ',', ' ') }} DH</span></span>
                </div>
            </div>


            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Ma Dotation</div>
                    <div class="text-2xl font-extrabold text-indigo-600">{{ number_format($stats['dotation'], 2, ',', ' ') }} <small class="text-xs font-semibold text-slate-400">DH</small></div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Consommé (Approuvé)</div>
                    <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['montant_approuve'], 2, ',', ' ') }} <small class="text-xs font-semibold text-slate-400">DH</small></div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">En attente (Bloqué)</div>
                    <div class="text-2xl font-extrabold text-amber-500">{{ number_format($stats['montant_attente'], 2, ',', ' ') }} <small class="text-xs font-semibold text-slate-400">DH</small></div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 border border-slate-100 bg-slate-50/50">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Reliquat Disponible</div>
                    <div class="text-2xl font-extrabold {{ $stats['reliquat'] <= 0 ? 'text-rose-500' : 'text-slate-800' }}">{{ number_format($stats['reliquat'], 2, ',', ' ') }} <small class="text-xs font-semibold text-slate-400">DH</small></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Propositions Actions -->
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 border border-slate-100 flex flex-col">
                    <h3 class="font-bold text-lg text-slate-900 mb-4 flex items-center justify-between">
                        <span>Mes Propositions de Répartition</span>
                        <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full">{{ $stats['propositions_count'] }}</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-8 flex-grow">Agrégez votre dotation sur les différentes lignes budgétaires existantes.</p>
                    <div class="flex space-x-3 mt-auto">
                        <a href="{{ route('emetteur.lignes.create') }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300">Nouvelle proposition</a>
                        <a href="{{ route('emetteur.lignes.index') }}" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300">Voir la liste</a>
                    </div>
                </div>

                <!-- Engagements Actions -->
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 border border-slate-100 flex flex-col">
                    <h3 class="font-bold text-lg text-slate-900 mb-4 flex items-center justify-between">
                        <span>Mes Bons de Commande</span>
                        <span class="bg-violet-50 text-violet-600 text-xs font-bold px-3 py-1 rounded-full">{{ $stats['engagements_count'] }}</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-8 flex-grow">Saisissez vos bons de commande sur vos lignes budgétaires approuvées.</p>
                    <div class="flex space-x-3 mt-auto">
                        <a href="{{ route('emetteur.engagements.create') }}" class="flex-1 text-center bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300">Créer un BC</a>
                        <a href="{{ route('emetteur.engagements.index') }}" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300">Voir la liste</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
