<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bons de Commande (Engagements)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabs & Actions -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <nav class="flex space-x-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 max-w-fit w-full sm:w-auto">
                    <a href="{{ route('admin.engagements.index', ['statut' => 'en_attente', 'saison' => $saison]) }}" class="{{ $status === 'en_attente' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        En attente
                    </a>
                    <a href="{{ route('admin.engagements.index', ['statut' => 'approuve', 'saison' => $saison]) }}" class="{{ $status === 'approuve' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Approuvés
                    </a>
                    <a href="{{ route('admin.engagements.index', ['statut' => 'rejete', 'saison' => $saison]) }}" class="{{ $status === 'rejete' ? 'bg-rose-50 text-rose-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Rejetés
                    </a>
                </nav>

                <!-- Filters & Export -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Saison Dropdown -->
                    <form method="GET" action="{{ route('admin.engagements.index') }}" class="flex items-center" id="filter-form">
                        <input type="hidden" name="statut" value="{{ $status }}">
                        <select name="saison" onchange="document.getElementById('filter-form').submit()" class="rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-slate-600 bg-white">
                            <option value="">Toutes les saisons</option>
                            @foreach($saisons as $s)
                                <option value="{{ $s }}" {{ $saison == $s ? 'selected' : '' }}>Saison {{ $s }}</option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Export Buttons -->
                    <a href="{{ route('admin.engagements.export.excel', ['statut' => $status, 'saison' => $saison]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-sm font-semibold transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </a>
                    <a href="{{ route('admin.engagements.export.pdf', ['statut' => $status, 'saison' => $saison]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-sm font-semibold transition" target="_blank">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        PDF
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                            <th class="p-4">Date</th>
                            <th class="p-4">Émetteur</th>
                            <th class="p-4">Objet</th>
                            <th class="p-4">Montant Total TTC (DH)</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engagements as $engagement)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                <td class="p-4 text-slate-600">{{ $engagement->created_at->format('d/m/Y') }}</td>
                                <td class="p-4 font-semibold text-slate-800">{{ $engagement->emetteur->user->name }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-700">{{ $engagement->objet }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $engagement->fournisseur?->nom ?? 'Fournisseur non défini' }}</div>
                                </td>
                                <td class="p-4 font-bold text-indigo-600">{{ number_format($engagement->montant_total, 2, ',', ' ') }}</td>
                                
                                <td class="p-4 text-center">
                                    <a href="{{ route('admin.engagements.show', $engagement) }}" class="inline-flex items-center justify-center bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-sm transition font-medium">Voir détails</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500 font-medium">Aucun bon de commande {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
