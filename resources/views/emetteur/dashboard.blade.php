<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Espace (Émetteur)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Bienvenue {{ $emetteur->user->name }}</h3>
                <p class="text-gray-600">Saison budgétaire active : <strong>{{ $emetteur->budget->saison }}</strong></p>
            </div>

            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-semibold uppercase mb-1">Ma Dotation</div>
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['dotation'], 2, ',', ' ') }} <small class="text-sm">DH</small></div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-semibold uppercase mb-1">Consommé (Approuvé)</div>
                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['montant_approuve'], 2, ',', ' ') }} <small class="text-sm">DH</small></div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500 text-sm font-semibold uppercase mb-1">En attente (Bloqué)</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['montant_attente'], 2, ',', ' ') }} <small class="text-sm">DH</small></div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-500 bg-gray-50">
                    <div class="text-gray-500 text-sm font-semibold uppercase mb-1">Reliquat Disponible</div>
                    <div class="text-2xl font-bold {{ $stats['reliquat'] <= 0 ? 'text-red-600' : 'text-gray-800' }}">{{ number_format($stats['reliquat'], 2, ',', ' ') }} <small class="text-sm">DH</small></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Propositions Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center justify-between">
                        <span>Mes Propositions de Répartition</span>
                        <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">{{ $stats['propositions_count'] }}</span>
                    </h3>
                    <p class="text-gray-600 text-sm mb-6 flex-grow">Agrégez votre dotation sur les différentes lignes budgétaires existantes.</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('emetteur.lignes.create') }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nouvelle proposition</a>
                        <a href="{{ route('emetteur.lignes.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Voir la liste</a>
                    </div>
                </div>

                <!-- Engagements Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center justify-between">
                        <span>Mes Bons de Commande</span>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">{{ $stats['engagements_count'] }}</span>
                    </h3>
                    <p class="text-gray-600 text-sm mb-6 flex-grow">Saisissez vos bons de commande sur vos lignes budgétaires approuvées.</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('emetteur.engagements.create') }}" class="flex-1 text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Créer un BC</a>
                        <a href="{{ route('emetteur.engagements.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Voir la liste</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
