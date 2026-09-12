<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Détail Expression de Besoins') }}
            </h2>
            <x-status-badge :status="$engagement->statut" class="text-sm px-4 py-1.5 shadow-sm" />
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
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm text-rose-700 font-medium space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($engagement->statut === 'rejete')
                <div class="mb-8 bg-rose-50 border-l-4 border-rose-500 p-5 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-rose-800 mb-2">Votre expression de besoins a été refusée par l'administration. Motif :</h3>
                    <p class="text-rose-700 italic text-sm leading-relaxed">{{ $engagement->motif_refus }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 mb-8 p-6 lg:p-8 flex flex-col md:flex-row justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2 text-slate-800">{{ $engagement->commentaire ?? 'Aucun commentaire' }}</h3>
                    <p class="text-slate-500 mb-6 text-sm">Créé le {{ $engagement->created_at->format('d/m/Y à H:i') }}</p>
                    
                    <div class="mb-4">
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">Fournisseur</span>
                        <span class="font-medium text-slate-700 text-lg">{{ $engagement->fournisseur_nom ?? $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
                    </div>
                    
                    <div>
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">Imputé sur la ligne</span>
                        <div class="inline-flex items-center">
                            <span class="font-mono text-xs bg-slate-50 border border-slate-200 text-slate-500 px-2 py-1 rounded-md">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                            <span class="text-sm font-medium text-slate-700 ml-3">{{ $engagement->ligneProposee?->ligne?->nom ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 md:mt-0 md:text-right flex flex-col justify-end">
                    <div class="border-t border-slate-100 pt-6 md:border-t-0 md:pt-0">
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">TVA Globale</span>
                        <span class="text-lg font-bold text-slate-700">{{ $engagement->tva }} %</span>
                        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mt-4 mb-1">Montant Total HT</span>
                        <span class="text-xl font-bold text-slate-800">{{ number_format($engagement->total_ht, 2, ',', ' ') }} DH</span>
                        <span class="text-xs uppercase tracking-wider text-indigo-400 font-semibold block mt-4 mb-1">Montant Total TTC Estimé</span>
                        <span class="text-4xl font-extrabold text-indigo-600 tracking-tight">{{ number_format($engagement->total_ttc, 2, ',', ' ') }} <small class="text-lg text-indigo-400 font-semibold">DH</small></span>
                    </div>
                </div>
            </div>

            <!-- Articles Section -->
            <div class="bg-white shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Articles & Besoins liés</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 font-semibold border-b border-slate-100">
                                <th class="p-4">Intitulé de l'article</th>
                                <th class="p-4 text-center">Qté</th>
                                <th class="p-4 text-right">PU HT (DH)</th>
                                <th class="p-4 text-right">Total HT (DH)</th>
                                <th class="p-4 text-center">Livraison</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($engagement->besoins as $besoin)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150 {{ $besoin->is_delivered ? 'bg-emerald-50/30' : '' }}">
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-800">{{ $besoin->intitule }}</div>
                                        @if($besoin->description)
                                            <div class="text-xs text-slate-500 mt-1">{{ $besoin->description }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center text-slate-700">{{ $besoin->quantite }}</td>
                                    <td class="p-4 text-right text-slate-600">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}</td>
                                    <td class="p-4 text-right font-bold text-indigo-600">{{ number_format($besoin->montant, 2, ',', ' ') }}</td>
                                    <td class="p-4 text-center border-l border-slate-50 transition duration-300" id="besoin-status-{{ $besoin->id }}">
                                        @if($engagement->statut === 'approuve')
                                            <div class="flex items-center justify-center">
                                                <input 
                                                    type="checkbox" 
                                                    class="h-5 w-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 livraison-check cursor-pointer transition-all hover:scale-110 shadow-sm" 
                                                    data-id="{{ $besoin->id }}" 
                                                    data-url="{{ route('emetteur.besoins.livraison', $besoin->id) }}"
                                                    {{ $besoin->is_delivered ? 'checked' : '' }}
                                                >
                                            </div>
                                        @else
                                            @if($besoin->is_delivered)
                                                <span class="text-emerald-700 text-[10px] font-black uppercase tracking-widest bg-emerald-100 px-2.5 py-1 rounded-md">Livré</span>
                                            @else
                                                <span class="text-slate-500 text-[10px] font-black uppercase tracking-widest bg-slate-100 px-2.5 py-1 rounded-md">En attente</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 font-medium italic">Aucun article dans cette expression de besoins.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                                if (data.success && isChecked) {
                                    row.classList.add('bg-emerald-50/30');
                                } else {
                                    row.classList.remove('bg-emerald-50/30');
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
                <div class="bg-white shadow-sm rounded-2xl border border-indigo-100 mb-8 p-6 lg:p-8 relative overflow-hidden">
                    <div class="absolute inset-y-0 left-0 w-1 bg-indigo-500"></div>
                    <h3 class="font-bold text-slate-800 text-lg mb-6">Ajouter un article supplémentaire</h3>
                    
                    <form action="{{ route('emetteur.engagements.besoins.store', $engagement->id) }}" method="POST">
                        @csrf
                        <div class="flex flex-wrap md:flex-nowrap space-y-4 md:space-y-0 md:space-x-4 items-end">
                            <div class="w-full md:w-1/3">
                                <label class="block text-slate-700 text-xs font-bold mb-1.5 uppercase tracking-wide">Intitulé</label>
                                <input type="text" name="intitule" class="shadow-sm border-slate-200 rounded-xl w-full py-2.5 px-3 text-sm text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="block text-slate-700 text-xs font-bold mb-1.5 uppercase tracking-wide">Description</label>
                                <input type="text" name="description" class="shadow-sm border-slate-200 rounded-xl w-full py-2.5 px-3 text-sm text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            <div class="w-full md:w-1/6">
                                <label class="block text-slate-700 text-xs font-bold mb-1.5 uppercase tracking-wide">Quantité</label>
                                <input type="number" name="quantite" value="1" min="1" class="shadow-sm border-slate-200 rounded-xl w-full py-2.5 px-3 text-sm text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                            </div>
                            <div class="w-full md:w-1/6">
                                <label class="block text-slate-700 text-xs font-bold mb-1.5 uppercase tracking-wide">PU HT (DH)</label>
                                <input type="number" step="0.01" name="prix_unitaire" class="shadow-sm border-slate-200 rounded-xl w-full py-2.5 px-3 text-sm text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                            </div>
                            <div class="w-full md:w-auto">
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm text-sm whitespace-nowrap transition duration-300">
                                    + Ajouter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('emetteur.engagements.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour à mes expressions de besoins
                </a>
                <a href="{{ route('emetteur.engagements.download', $engagement->id) }}"
                   class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Télécharger le PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
