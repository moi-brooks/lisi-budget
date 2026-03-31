<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détail Bon de Commande') }}
            </h2>
            <x-status-badge :status="$engagement->statut" class="text-sm px-3 py-1" />

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($engagement->statut === 'rejete')
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                    <h3 class="font-bold text-red-800">Votre bon de commande a été refusé par l'administration. Motif :</h3>
                    <p class="text-red-700 italic mt-1">{{ $engagement->motif_refus }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6 flex flex-col md:flex-row justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-1">{{ $engagement->commentaire ?? 'Aucun commentaire' }}</h3>
                    <p class="text-gray-500 mb-4 text-sm">Créé le {{ $engagement->created_at->format('d/m/Y à H:i') }}</p>
                    
                    <div class="mb-2">
                        <span class="text-sm text-gray-500 block">Fournisseur</span>
                        <span class="font-semibold">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-500 block">Imputé sur la ligne</span>
                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                        <span class="text-sm ml-2 font-semibold">{{ $engagement->ligneProposee?->ligne?->nom ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0 md:text-right flex flex-col justify-end">
                    <div class="border-t pt-4 md:border-t-0 md:pt-0">
                        <span class="text-sm text-gray-500 block">TVA Globale</span>
                        <span class="text-lg font-bold text-gray-700">{{ $engagement->tva }} %</span>
                        <span class="text-sm text-gray-500 block mt-2">Montant Total HT</span>
                        <span class="text-xl font-bold text-gray-800">{{ number_format($engagement->total_ht, 2, ',', ' ') }} DH</span>
                        <span class="text-sm text-gray-500 block mt-2">Montant Total TTC Estimé</span>
                        <span class="text-3xl font-bold text-blue-600">{{ number_format($engagement->total_ttc, 2, ',', ' ') }} <small class="text-sm text-gray-500">DH</small></span>
                    </div>
                </div>
            </div>

            <!-- Articles Section -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Articles & Besoins liés</h3>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-xs text-gray-600 border-b uppercase">
                            <th class="p-3">Intitulé de l'article</th>
                            <th class="p-3 text-center">Qté</th>
                            <th class="p-3 text-right">PU HT (DH)</th>
                            <th class="p-3 text-right">Total HT (DH)</th>
                            <th class="p-3 text-center">Livraison</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engagement->besoins as $besoin)
                            <tr class="border-b hover:bg-gray-50 {{ $besoin->livre ? 'bg-green-50' : '' }}">
                                <td class="p-3">
                                    <div class="font-semibold">{{ $besoin->intitule }}</div>
                                    @if($besoin->description)
                                        <div class="text-xs text-gray-500">{{ $besoin->description }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-center">{{ $besoin->quantite }}</td>
                                <td class="p-3 text-right">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}</td>
                                <td class="p-3 text-right font-bold">{{ number_format($besoin->montant, 2, ',', ' ') }}</td>
                                <td class="p-3 text-center border-l">
                                <td class="p-3 text-center border-l transition duration-300" id="besoin-status-{{ $besoin->id }}">
                                    @if($engagement->statut === 'approuve')
                                        <div class="flex items-center justify-center">
                                            <input 
                                                type="checkbox" 
                                                class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 livraison-check cursor-pointer transition-all hover:scale-110" 
                                                data-id="{{ $besoin->id }}" 
                                                data-url="{{ route('emetteur.besoins.livraison', $besoin->id) }}"
                                                {{ $besoin->is_delivered ? 'checked' : '' }}
                                            >
                                        </div>
                                    @else
                                        @if($besoin->is_delivered)
                                            <span class="text-green-600 text-[10px] font-black uppercase tracking-widest bg-green-50 px-2 py-1 rounded">Livré</span>
                                        @else
                                            <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest bg-gray-50 px-2 py-1 rounded">En attente</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500 italic">Aucun article dans ce bon de commande.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @push('scripts')
            <script>
                document.querySelectorAll('.livraison-check').forEach(checkbox => {
                    checkbox.addEventListener('change', async function() {
                        const id = this.dataset.id;
                        const url = this.dataset.url;
                        const isChecked = this.checked;
                        const row = this.closest('tr');
                        const statusCell = document.getElementById(`besoin-status-${id}`);

                        // Visual feedback (loading)
                        statusCell.style.opacity = '0.5';
                        checkbox.disabled = true;

                        try {
                            const response = await fetch(url, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ is_delivered: isChecked ? 1 : 0 })
                            });

                            const data = await response.json();

                            if (data.success) {
                                // Update row style
                                if (data.is_delivered) {
                                    row.classList.add('bg-green-50');
                                } else {
                                    row.classList.remove('bg-green-50');
                                }
                            } else {
                                alert(data.message || 'Erreur lors de la mise à jour.');
                                this.checked = !isChecked; // Revert
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Une erreur réseau est survenue.');
                            this.checked = !isChecked; // Revert
                        } finally {
                            statusCell.style.opacity = '1';
                            checkbox.disabled = false;
                        }
                    });
                });
            </script>
            @endpush


            <!-- Add Article Form -->
            @if($engagement->statut === 'en_attente')
                <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6 border-l-4 border-indigo-500">
                    <h3 class="font-bold text-gray-800 mb-4">Ajouter un article supplémentaire</h3>
                    
                    <form action="{{ route('emetteur.engagements.besoins.store', $engagement->id) }}" method="POST">
                        @csrf
                        <div class="flex flex-wrap md:flex-nowrap space-y-4 md:space-y-0 md:space-x-4 items-end">
                            <div class="w-full md:w-1/3">
                                <label class="block text-gray-700 text-xs font-bold mb-1">Intitulé</label>
                                <input type="text" name="intitule" class="shadow appearance-none border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="block text-gray-700 text-xs font-bold mb-1">Description</label>
                                <input type="text" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-sm text-gray-700">
                            </div>
                            <div class="w-full md:w-1/6">
                                <label class="block text-gray-700 text-xs font-bold mb-1">Quantité</label>
                                <input type="number" name="quantite" value="1" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-full md:w-1/6">
                                <label class="block text-gray-700 text-xs font-bold mb-1">PU HT (DH)</label>
                                <input type="number" step="0.01" name="prix_unitaire" class="shadow appearance-none border rounded w-full py-2 px-3 text-sm text-gray-700" required>
                            </div>
                            <div class="w-full md:w-auto">
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm whitespace-nowrap">
                                    + Ajouter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('emetteur.engagements.index') }}" class="text-blue-600 hover:underline">&larr; Retour à mes bons de commande</a>
            </div>
        </div>
    </div>
</x-app-layout>
