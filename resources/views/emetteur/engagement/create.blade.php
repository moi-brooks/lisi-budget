<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-primary/10 rounded-2xl">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('emetteur.engagements.index') }}" class="hover:text-primary transition-colors italic">Mes Engagements</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary-muted font-black italic">Initialiser BC</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Nouveau Bon de Commande
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if($errors->any())
                <div class="p-6 bg-rose-50 border-0 rounded-[32px] animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-center space-x-3 text-rose-800 mb-4">
                        <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span class="font-bold uppercase tracking-widest text-xs">Erreurs de validation</span>
                    </div>
                    <ul class="space-y-1 ml-8 list-disc text-sm text-rose-600 font-medium leading-relaxed">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($lignesApprouvees->isEmpty())
                <div class="bg-surface-container-lowest rounded-[48px] p-12 text-center border-0 shadow-sm">
                    <div class="w-24 h-24 bg-amber-50 rounded-[32px] flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-2xl font-display font-bold text-slate-900 mb-4 tracking-tight">Aucune ligne approuvée</h3>
                    <p class="text-slate-500 max-w-md mx-auto leading-relaxed font-medium">Vous devez d'abord obtenir l'approbation d'une proposition budgétaire par l'administration avant de pouvoir saisir un bon de commande.</p>
                    <div class="mt-10">
                        <a href="{{ route('emetteur.propositions.create') }}" class="inline-flex items-center px-8 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/25 hover:scale-105 transition-all active:scale-95">
                            Faire une proposition
                        </a>
                    </div>
                </div>
            @else

            <form action="{{ route('emetteur.engagements.store') }}" method="POST" class="space-y-10">
                @csrf
                
                {{-- Global Info Section --}}
                <div class="bg-surface-container-low rounded-[48px] p-10 border-0 shadow-sm overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-primary/5 rounded-full -translate-y-24 translate-x-24"></div>
                    
                    <div class="relative z-10 space-y-10">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            <h3 class="text-xl font-display font-bold text-slate-900 tracking-tight">Informations Générales</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div class="lg:col-span-2 space-y-2 group">
                                <label for="ligne_proposee_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Imputer sur la ligne</label>
                                <div class="relative">
                                    <select name="ligne_proposee_id" id="ligne_proposee_id" 
                                        class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-bold appearance-none focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                        required>
                                        <option value="" disabled selected>-- Choisir une ligne --</option>
                                        @foreach($lignesApprouvees as $ligne_prop)
                                            <option value="{{ $ligne_prop->id }}" {{ old('ligne_proposee_id', request('ligne_id')) == $ligne_prop->id ? 'selected' : '' }}>
                                                {{ $ligne_prop->ligne->code_complet }} - {{ $ligne_prop->ligne->nom }} (Dispo: {{ number_format($ligne_prop->montant, 0) }} DH)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" /></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 group">
                                <label for="fournisseur_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Fournisseur</label>
                                <div class="relative">
                                    <select name="fournisseur_id" id="fournisseur_id" 
                                        class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-bold appearance-none focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm">
                                        <option value="">-- Non défini / En attente --</option>
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                                {{ $fournisseur->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" /></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 group">
                                <label for="date" class="block text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted ml-1 group-focus-within:text-primary transition-colors italic">Date de commande</label>
                                <input type="date" name="date" id="date" 
                                    class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-bold focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                    value="{{ old('date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="lg:col-span-3 space-y-2 group">
                                <label for="commentaire" class="block text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted ml-1 group-focus-within:text-primary transition-colors italic">Commentaire / Objet</label>
                                <input type="text" name="commentaire" id="commentaire" 
                                    class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-medium placeholder:text-slate-300 focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                    placeholder="Ex: Achat de fournitures de bureau pour le labo..."
                                    value="{{ old('commentaire') }}">
                            </div>

                            <div class="space-y-2 group">
                                <label for="tva" class="block text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted ml-1 group-focus-within:text-primary transition-colors italic">TVA Globale (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="tva" id="tva" 
                                        class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-900 font-black text-xl text-center focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                        value="{{ old('tva', 20) }}" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-xs font-black text-slate-300 italic">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Articles Section --}}
                <div class="bg-white rounded-[48px] p-10 border-0 shadow-sm space-y-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            <h3 class="text-xl font-display font-bold text-slate-900 tracking-tight">Articles & Besoins</h3>
                        </div>
                        <button type="button" id="add-article-btn" 
                            class="group flex items-center space-x-2 px-6 py-3 bg-emerald-50 text-emerald-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                            <svg class="w-4 h-4 transform group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            <span>Ajouter un article</span>
                        </button>
                    </div>

                    <div id="articles-container" class="space-y-6">
                        {{-- First Row --}}
                        <div class="article-row group relative grid grid-cols-1 md:grid-cols-12 gap-4 p-8 bg-surface-container-lowest rounded-[32px] border-2 border-transparent hover:border-primary/10 transition-all duration-300 shadow-sm">
                            <div class="md:col-span-5 space-y-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic">Intitulé de l'article</label>
                                <input type="text" name="intitule[]" placeholder="Ex: Ramettes papier A4" 
                                    class="w-full bg-white border-slate-100 border-2 rounded-xl px-4 py-3 text-slate-700 font-bold focus:border-primary focus:ring-0 focus:outline-none transition-all" required>
                            </div>
                            <div class="md:col-span-4 space-y-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic">Description / Détails</label>
                                <input type="text" name="description[]" placeholder="80g, Blanc..." 
                                    class="w-full bg-white border-slate-100 border-2 rounded-xl px-4 py-3 text-slate-500 font-medium focus:border-primary focus:ring-0 focus:outline-none transition-all">
                            </div>
                            <div class="md:col-span-1 space-y-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic text-center">Qté</label>
                                <input type="number" name="quantite[]" step="1" value="1" min="1" 
                                    class="w-full bg-white border-slate-100 border-2 rounded-xl px-4 py-3 text-slate-700 font-bold text-center focus:border-primary focus:ring-0 focus:outline-none transition-all qte-input" required>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic text-right">PU HT (DH)</label>
                                <input type="number" step="0.01" name="prix_unitaire[]" placeholder="0.00" 
                                    class="w-full bg-white border-slate-100 border-2 rounded-xl px-4 py-3 text-primary font-black text-right focus:border-primary focus:ring-0 focus:outline-none transition-all pu-input" required>
                            </div>
                            
                            <button type="button" class="del-btn absolute -right-3 -top-3 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg hover:scale-110 active:scale-95 hidden" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Dynamic Total Display --}}
                    <div class="flex flex-col md:flex-row items-center justify-between p-10 bg-surface-container-low rounded-[40px] border-2 border-dashed border-slate-200">
                        <div class="flex items-center space-x-6 mb-6 md:mb-0">
                            <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-sm">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="font-display font-bold text-slate-800 text-lg">Estimation du Bon</h4>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Le total TTC sera calculé à l'approbation.</p>
                            </div>
                        </div>

                        <div class="text-center md:text-right">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total HT Prévisionnel</span>
                            <div class="flex items-baseline justify-center md:justify-end space-x-3">
                                <span id="total-ht-display" class="text-6xl font-display font-black text-primary tracking-tighter">0,00</span>
                                <span class="text-xl font-bold text-primary/30 uppercase tracking-widest">DH</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Actions --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-8 pt-10">
                    <a href="{{ route('emetteur.engagements.index') }}" 
                        class="group flex items-center space-x-2 text-slate-400 hover:text-rose-500 transition-colors duration-300 font-bold tracking-widest text-[10px] uppercase italic">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Annuler et retourner au tableau de bord</span>
                    </a>
                    
                    <button type="submit" 
                        class="group relative w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-700 hover:scale-[1.02] active:scale-[0.98] text-white font-bold py-6 px-12 rounded-[32px] shadow-2xl shadow-primary/30 transition-all duration-300 flex items-center justify-center space-x-4">
                        <span class="text-lg tracking-tight">Soumettre le Bon de Commande</span>
                        <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </form>

            @push('scripts')
            <script>
                function calculateTotalHT() {
                    let total = 0;
                    const rows = document.querySelectorAll('.article-row');
                    rows.forEach(row => {
                        const qte = parseFloat(row.querySelector('input[name="quantite[]"]').value) || 0;
                        const pu = parseFloat(row.querySelector('input[name="prix_unitaire[]"]').value) || 0;
                        total += qte * pu;
                    });
                    document.getElementById('total-ht-display').innerText = new Intl.NumberFormat('fr-FR', { 
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(total);
                }

                document.getElementById('articles-container').addEventListener('input', calculateTotalHT);

                document.getElementById('add-article-btn').addEventListener('click', function() {
                    const container = document.getElementById('articles-container');
                    const firstRow = container.querySelector('.article-row');
                    const row = firstRow.cloneNode(true);
                    
                    // Clear inputs in cloned row
                    row.querySelectorAll('input').forEach(input => input.value = '');
                    row.querySelector('input[name="quantite[]"]').value = '1';
                    
                    // Show delete button
                    const delBtn = row.querySelector('.del-btn');
                    delBtn.classList.remove('hidden');
                    delBtn.addEventListener('click', function() {
                        row.style.transform = 'translateX(20px)';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            calculateTotalHT();
                        }, 300);
                    });
                    
                    container.appendChild(row);
                    // Animation
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        row.style.transition = 'all 0.3s ease-out';
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    }, 50);
                    
                    calculateTotalHT();
                });
                
                // Initial calculation
                calculateTotalHT();
            </script>
            @endpush

            @endif
        </div>
    </div>
</x-app-layout>
