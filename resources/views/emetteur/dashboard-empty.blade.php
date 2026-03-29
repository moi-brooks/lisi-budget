<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration requise') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <svg class="mx-auto h-16 w-16 text-yellow-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Profil Émetteur non défini</h3>
                <p class="text-gray-600 mb-6 max-w-lg mx-auto">
                    Votre compte n'est actuellement rattaché à aucun profil d'émetteur de budget. 
                    Veuillez contacter l'administrateur du système pour qu'il vous affecte une dotation et un budget.
                </p>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
