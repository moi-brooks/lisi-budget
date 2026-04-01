@section('title', 'B.C. : ' . ($engagement->objet ?? 'Détails'))

<x-app-layout>
    <div class="space-y-8">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('emetteur.engagements.index') }}" class="inline-flex items-center text-[11px] font-bold uppercase tracking-widest text-slate-400 hover:text-navy transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour aux Bons de Commande
            </a>
            <x-status-badge :status="$engagement->statut" class="px-4 py-1.5 shadow-sm" />
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($engagement->statut === 'rejete')
            <div class="bg-rose-50 border-l-4 border-rose-500 p-6 rounded-r-2xl">
                <h3 class="text-xs font-black uppercase tracking-widest text-rose-800 mb-2">Motif du Refus</h3>
                <p class="text-rose-700 text-sm italic leading-relaxed">{{ $engagement->motif_refus }}</p>
            </div>
        @endif

        <!-- Hero Card : Summary -->
        <div class="bg-white rounded-[48px] p-10 border border-slate-100 shadow-[0_40px_80px_-40px_rgba(31,78,121,0.08)]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl font-extrabold text-navy tracking-tight mb-2">{{ $engagement->objet ?? $engagement->commentaire ?? 'Sans objet' }}</h1>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Créé le {{ $engagement->created_at->format('d/m/Y') }} à {{ $engagement->created_at->format('H:i') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-300 block mb-2">Fournisseur</span>
                            <span class="text-sm font-bold text-slate-700 uppercase tracking-tighter">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-300 block mb-2">Imputation</span>
                            <span class="font-mono text-[11px] text-navy bg-slate-50 px-2 py-1 rounded border border-slate-100">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-100 rounded-[32px] p-8 flex flex-col justify-center items-end">
                    <div class="text-right space-y-4 w-full">
                        <div class="flex justify-between items-baseline border-b border-white pb-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total HT</span>
                            <span class="text-lg font-mono font-bold text-slate-600">{{ number_format($engagement->total_ht, 2, ',', ' ') }} <small class="text-[10px]">DH</small></span>
                        </div>
                        <div class="flex justify-between items-baseline border-b border-white pb-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">TVA ({{ $engagement->tva }}%)</span>
                            <span class="text-lg font-mono font-bold text-slate-600">{{ number_format($engagement->total_ttc - $engagement->total_ht, 2, ',', ' ') }} <small class="text-[10px]">DH</small></span>
                        </div>
                        <div class="pt-4">
                            <span class="text-[11px] font-black uppercase tracking-widest text-navy block mb-1">Montant Total TTC Estimé</span>
                            <div class="text-5xl font-black text-navy tracking-tighter leading-none">
                                {{ number_format($engagement->total_ttc, 2, ',', ' ') }}
                                <span class="text-xl font-normal text-slate-400">DH</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="bg-white rounded-[32px] border border-slate-100 overflow-hidden shadow-sm">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xs font-black uppercase tracking-widest text-navy">Articles & Besoins liés</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 uppercase text-[10px] text-slate-400 font-black tracking-widest">
                            <th class="px-8 py-4">Article</th>
                            <th class="px-4 py-4 text-center w-20">Qté</th>
                            <th class="px-4 py-4 text-right">PU HT</th>
                            <th class="px-8 py-4 text-right">Total HT</th>
                            <th class="px-8 py-4 text-center">Status Livraison</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($engagement->besoins as $besoin)
                            <tr class="hover:bg-slate-50/50 transition duration-150 {{ $besoin->is_delivered ? 'bg-emerald-50/20' : '' }}">
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-slate-700 leading-tight">{{ $besoin->intitule }}</div>
                                    @if($besoin->description)
                                        <div class="text-[11px] text-slate-400 mt-1 uppercase tracking-wider font-medium">{{ $besoin->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-5 text-center font-mono text-xs text-slate-500">{{ $besoin->quantite }}</td>
                                <td class="px-4 py-5 text-right font-mono text-xs text-slate-500">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}</td>
                                <td class="px-8 py-5 text-right font-mono text-sm text-navy font-bold">{{ number_format($besoin->montant, 2, ',', ' ') }}</td>
                                <td class="px-8 py-5 text-center" id="besoin-status-{{ $besoin->id }}">
                                    @if($engagement->statut === 'approuve')
                                        <div class="flex items-center justify-center">
                                            <input type="checkbox" 
                                                class="h-5 w-5 text-navy rounded border-slate-300 focus:ring-navy livraison-check cursor-pointer shadow-sm transition-transform hover:scale-110" 
                                                data-id="{{ $besoin->id }}" 
                                                data-url="{{ route('emetteur.besoins.livraison', $besoin->id) }}"
                                                {{ $besoin->is_delivered ? 'checked' : '' }}>
                                        </div>
                                    @else
                                        @if($besoin->is_delivered)
                                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Livré</span>
                                        @else
                                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-100 px-3 py-1 rounded-full">Attente</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic text-sm font-medium">Aucun article enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Article Form -->
        @if($engagement->statut === 'en_attente')
            <div class="bg-navy rounded-[32px] p-10 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-black uppercase tracking-widest text-white/60 mb-8">Ajouter un article</h3>
                    
                    <form action="{{ route('emetteur.engagements.besoins.store', $engagement->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">Intitulé</label>
                                <input type="text" name="intitule" class="w-full bg-white/10 border-white/20 rounded-xl py-3 px-4 text-white text-sm focus:bg-white/20 focus:border-white/40 focus:ring-0 transition duration-200" placeholder="Ex: Ramettes de papier" required>
                            </div>
                            <div class="md:col-span-2 lg:col-span-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">Description</label>
                                <input type="text" name="description" class="w-full bg-white/10 border-white/20 rounded-xl py-3 px-4 text-white text-sm focus:bg-white/20 focus:border-white/40 focus:ring-0 transition duration-200" placeholder="Optionnel">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">Quantité</label>
                                <input type="number" name="quantite" value="1" min="1" class="w-full bg-white/10 border-white/20 rounded-xl py-3 px-4 text-white text-sm focus:bg-white/20 focus:border-white/40 focus:ring-0 transition duration-200" required>
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">PU HT (DH)</label>
                                <input type="number" step="0.01" name="prix_unitaire" class="w-full bg-white/10 border-white/20 rounded-xl py-3 px-4 text-white text-sm focus:bg-white/20 focus:border-white/40 focus:ring-0 transition duration-200" required>
                            </div>
                            <div class="md:col-span-4 lg:col-span-5 flex justify-end">
                                <button type="submit" class="bg-white text-navy font-black text-xs uppercase tracking-[0.2em] px-10 py-4 rounded-xl hover:bg-slate-100 transition duration-300">
                                    Enregistrer l'article
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Abstract visual element -->
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
            </div>
        @endif
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
                        if (isChecked) {
                            row.classList.add('bg-emerald-50/20');
                        } else {
                            row.classList.remove('bg-emerald-50/20');
                        }
                    } else {
                        alert(data.message || 'Erreur lors de la mise à jour.');
                        this.checked = !isChecked;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Une erreur réseau est survenue.');
                    this.checked = !isChecked;
                } finally {
                    statusCell.style.opacity = '1';
                    checkbox.disabled = false;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
