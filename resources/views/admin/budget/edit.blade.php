<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-primary/10 rounded-2xl">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <li><a href="{{ route('admin.budgets.index') }}" class="hover:text-primary transition-colors">Budgets</a></li>
                        <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-slate-600">Modifier</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Modifier le Budget
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-surface-container-low rounded-[48px] p-10 border-0 shadow-sm overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-16 translate-x-16"></div>
                
                <form action="{{ route('admin.budgets.update', $budget) }}" method="POST" class="relative z-10 space-y-10">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            <h3 class="text-lg font-display font-bold text-slate-900 tracking-tight">Paramètres du Budget</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2 group">
                                <label for="annee" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Année Fiscale</label>
                                <input type="number" name="annee" id="annee" 
                                    class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-black text-xl focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                    value="{{ old('annee', $budget->annee) }}" required>
                                @error('annee') <span class="text-rose-500 text-xs font-bold mt-1 block ml-4 tracking-tight">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2 group">
                                <label for="saison" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Saison / Intitulé</label>
                                <input type="text" name="saison" id="saison" 
                                    class="w-full bg-white border-slate-200 border-2 rounded-2xl px-5 py-4 text-slate-700 font-bold placeholder-slate-300 focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm"
                                    placeholder="Ex: 2024-2025"
                                    value="{{ old('saison', $budget->saison) }}" required>
                                @error('saison') <span class="text-rose-500 text-xs font-bold mt-1 block ml-4 tracking-tight">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2 group md:col-span-2">
                                <label for="total" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-primary transition-colors italic">Enveloppe Budgétaire Totale</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="total" id="total" 
                                        class="w-full bg-white border-slate-200 border-2 rounded-[32px] px-8 py-6 text-slate-900 font-black text-4xl placeholder-slate-200 focus:border-primary focus:ring-0 focus:outline-none transition-all duration-300 shadow-sm pr-20"
                                        placeholder="0.00"
                                        value="{{ old('total', $budget->total) }}" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-8 pointer-events-none">
                                        <span class="text-xl font-black text-slate-300 uppercase tracking-widest">DH</span>
                                    </div>
                                </div>
                                @error('total') <span class="text-rose-500 text-xs font-bold mt-1 block ml-4 tracking-tight">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Footer --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-10 mt-10 border-t border-slate-100">
                        <a href="{{ route('admin.budgets.index') }}" 
                           class="group flex items-center space-x-2 text-slate-400 hover:text-rose-500 transition-colors duration-300 font-bold tracking-widest text-[10px] uppercase">
                            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Annuler les modifications</span>
                        </a>
                        
                        <button type="submit" 
                            class="w-full sm:w-auto bg-gradient-to-r from-primary to-indigo-700 hover:scale-105 active:scale-95 text-white font-bold py-4 px-10 rounded-[24px] shadow-xl shadow-primary/25 transition-all duration-300 flex items-center justify-center space-x-3">
                            <span>Mettre à jour le Budget</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
