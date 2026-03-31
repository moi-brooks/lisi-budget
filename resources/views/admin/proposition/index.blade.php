<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Propositions de Budget') }}
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

            <!-- Tabs -->
            <div class="mb-8">
                <nav class="flex space-x-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 max-w-fit">
                    <a href="{{ route('admin.propositions.index', ['statut' => 'en_attente']) }}" class="{{ $status === 'en_attente' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        En attente
                    </a>
                    <a href="{{ route('admin.propositions.index', ['statut' => 'approuve']) }}" class="{{ $status === 'approuve' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Approuvées
                    </a>
                    <a href="{{ route('admin.propositions.index', ['statut' => 'rejete']) }}" class="{{ $status === 'rejete' ? 'bg-rose-50 text-rose-700' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }} rounded-xl px-5 py-2.5 text-sm font-semibold transition duration-300">
                        Rejetées
                    </a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                            <th class="p-4">Date</th>
                            <th class="p-4">Émetteur</th>
                            <th class="p-4">Ligne Budgétaire</th>
                            <th class="p-4">Montant (DH)</th>
                            <th class="p-4">Justification</th>
                            @if($status === 'en_attente')
                                <th class="p-4 text-center">Actions</th>
                            @elseif($status === 'rejete')
                                <th class="p-4">Motif de rejet</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propositions as $prop)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition duration-150">
                                <td class="p-4 text-slate-600">{{ $prop->created_at->format('d/m/Y') }}</td>
                                <td class="p-4 font-semibold text-slate-800">{{ $prop->emetteur->user->name }}</td>
                                <td class="p-4">
                                    <div class="font-mono text-xs text-slate-400 mb-1 border border-slate-200 inline-block px-1.5 py-0.5 rounded bg-slate-50">{{ $prop->ligne->code_complet }}</div>
                                    <div class="text-slate-700 font-medium">{{ $prop->ligne->nom }}</div>
                                </td>
                                <td class="p-4 font-bold text-indigo-600">{{ number_format($prop->montant, 2, ',', ' ') }}</td>
                                <td class="p-4 text-slate-500 italic">{{ Str::limit($prop->justification, 50) ?? '-' }}</td>
                                
                                @if($status === 'en_attente')
                                    <td class="p-4 text-center border-l border-slate-50">
                                        <div class="flex flex-col space-y-2">
                                            <form action="{{ route('admin.propositions.approve', $prop->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition rounded-lg px-3 py-1.5 font-semibold text-xs">Approuver</button>
                                            </form>
                                            <button @click="$dispatch('open-modal-reject', { id: '{{ $prop->id }}' })" class="w-full bg-rose-50 text-rose-700 hover:bg-rose-100 transition rounded-lg px-3 py-1.5 font-semibold text-xs">Rejeter</button>
                                        </div>

                                        <x-modal-reject :id="$prop->id" :route="route('admin.propositions.reject', $prop->id)" title="Rejeter la proposition" />
                                    </td>

                                @elseif($status === 'rejete')
                                    <td class="p-4 text-rose-600 font-medium">{{ $prop->motif_refus }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 font-medium">Aucune proposition {{ str_replace('_', ' ', $status) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
