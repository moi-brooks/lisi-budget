@props(['id', 'route', 'title' => 'Confirmer le rejet'])

<div 
    x-data="{ open: false, motif: '' }" 
    x-on:open-modal-reject.window="if($event.detail.id == '{{ $id }}') open = true"
    x-show="open" 
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" x-on:click="open = false">
            <div class="absolute inset-0 bg-navy/20 backdrop-blur-sm"></div>
        </div>

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden border border-slate-200 shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-6 pb-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-bold text-navy mb-4">{{ $title }}</h3>
                        <div>
                            <form :id="'form-reject-' + '{{ $id }}'" action="{{ $route }}" method="POST">
                                @csrf
                                <label for="motif_refus" class="block text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-2">Motif du rejet (min. 10 caractères)</label>
                                <textarea 
                                    name="motif_refus" 
                                    x-model="motif" 
                                    class="block w-full text-sm border-slate-200 rounded-lg focus:ring-navy focus:border-navy transition duration-150" 
                                    rows="4" 
                                    placeholder="Indiquez la raison du rejet..."
                                ></textarea>
                                <div class="mt-2 flex justify-between items-center">
                                    <p class="text-[10px] uppercase font-bold" :class="motif.length < 10 ? 'text-rose-500' : 'text-emerald-500'">
                                        <span x-text="motif.length"></span>/10 caractères min.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse space-x-reverse space-x-3">
                <button 
                    type="submit" 
                    :form="'form-reject-' + '{{ $id }}'" 
                    :disabled="motif.length < 10"
                    class="inline-flex justify-center rounded-lg px-4 py-2 bg-rose-600 text-xs font-bold uppercase tracking-widest text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150"
                >
                    Rejeter
                </button>
                <button 
                    type="button" 
                    x-on:click="open = false" 
                    class="inline-flex justify-center rounded-lg px-4 py-2 bg-white border border-slate-200 text-xs font-bold uppercase tracking-widest text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy transition duration-150"
                >
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
