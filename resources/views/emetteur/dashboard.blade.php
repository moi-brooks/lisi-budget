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

            <!-- Reliquat Faible Warning -->
            @if($emetteur->is_reliquat_faible)
                <div class="mb-8 bg-rose-50 border border-rose-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="flex items-center space-x-3 bg-gradient-to-r from-rose-500 to-red-500 px-5 py-3">
                        <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-white font-bold text-sm uppercase tracking-wider">Alerte : Reliquat Faible</p>
                    </div>
                    <div class="px-5 py-4">
                        <p class="text-rose-800 text-sm font-medium">Votre reliquat disponible est descendu en dessous de 10% de votre dotation initiale.</p>
                        <p class="text-rose-600 text-xs mt-1">Veuillez surveiller vos prochains engagements pour éviter tout dépassement de budget.</p>
                    </div>
                </div>
            @endif

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
                        <span>Mes Expressions de Besoins</span>
                        <span class="bg-violet-50 text-violet-600 text-xs font-bold px-3 py-1 rounded-full">{{ $stats['engagements_count'] }}</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-8 flex-grow">Saisissez vos expressions de besoins sur vos lignes budgétaires approuvées.</p>
                    <div class="flex space-x-3 mt-auto">
                        <a href="{{ route('emetteur.engagements.create') }}" class="flex-1 text-center bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300">Initialiser EB</a>
                        <a href="{{ route('emetteur.engagements.index') }}" class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-300">Voir la liste</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
