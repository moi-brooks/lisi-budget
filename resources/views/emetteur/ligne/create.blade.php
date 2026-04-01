<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-primary/10 rounded-2xl">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <li><a href="{{ route('emetteur.lignes.index') }}" class="hover:text-primary transition-colors">Répartitions</a></li>
                        <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-slate-600">Nouvelle Proposition</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Proposer une Répartition
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Reliquat Hero Card --}}
            <div class="bg-gradient-to-br from-primary to-indigo-900 rounded-[48px] p-10 text-white shadow-2xl shadow-primary/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-4 backdrop-blur-md">Solde Disponible</span>
                        <h3 class="text-slate-200 font-medium text-sm leading-relaxed max-w-xs">
                            Ceci est le montant maximum que vous pouvez affecter à des lignes aujourd'hui.
                        </h3>
                    </div>
                    <div class="text-right">
                        <div class="flex items-baseline justify-end space-x-3">
                            <span class="text-6xl font-black tracking-tighter">{{ number_format(auth()->user()->emetteur->reliquat, 0, ',', ' ') }}</span>
                            <span class="text-xl font-bold text-white/50 uppercase tracking-widest italic">DH</span>
                        </div>
                    </div>
                </div>
            </div>

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

            {{-- Form Card --}}
            <div class="bg-surface-container-low rounded-[48px] p-10 border-0 shadow-sm overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-16 translate-x-16"></div>
                
                <form action="{{ route('emetteur.lignes.store') }}" method="POST" class="relative z-10 space-y-10">
                    @csrf
                    
                    <div class="space-y-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            <h3 class="text-lg font-display font-bold text-slate-900 tracking-tight">Détails de la proposition</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-8">
                            <div class="space-y-2 group">
                                <label for="ligne_budgetaire_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Ligne Budgétaire Cible</label>
                                <div class="relative">
                                    <select name="ligne_budgetaire_id" id="ligne_budgetaire_id" 
                                        class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-bold appearance-none focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                        required>
                                        <option value="" disabled selected>-- Sélectionner une ligne --</option>
                                        @foreach($lignes as $ligne)
                                            <option value="{{ $ligne->id }}" {{ old('ligne_budgetaire_id') == $ligne->id ? 'selected' : '' }}>
                                                {{ $ligne->code_complet }} — {{ $ligne->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" /></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 group">
                                <label for="montant" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Montant à Affecter</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="montant" id="montant" 
                                        class="w-full bg-white border-slate-200 border-2 rounded-[32px] px-8 py-6 text-slate-900 font-black text-4xl placeholder-slate-200 focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm pr-20"
                                        placeholder="0.00"
                                        value="{{ old('montant') }}" required 
                                        max="{{ auth()->user()->emetteur->reliquat }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center px-8 pointer-events-none">
                                        <span class="text-xl font-black text-slate-300 uppercase tracking-widest">DH</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Footer --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-10 mt-10 border-t border-slate-100">
                        <a href="{{ route('emetteur.lignes.index') }}" 
                           class="group flex items-center space-x-2 text-slate-400 hover:text-rose-500 transition-colors duration-300 font-bold tracking-widest text-[10px] uppercase italic">
                            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Annuler la proposition</span>
                        </a>
                        
                        <button type="submit" 
                            class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-700 hover:scale-105 active:scale-95 text-white font-bold py-4 px-10 rounded-[24px] shadow-xl shadow-primary/25 transition-all duration-300 flex items-center justify-center space-x-3">
                            <span>Soumettre la Proposition</span>
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
