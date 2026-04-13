<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 flex flex-col justify-center border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Budgets Actifs</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $stats['budgets_count'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 flex flex-col justify-center border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Émetteurs</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $stats['emetteurs_count'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 flex flex-col justify-center border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Propositions en attente</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $stats['propositions_attente'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl p-6 flex flex-col justify-center border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Expressions de besoins en attente</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $stats['engagements_attente'] }}</div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-slate-100">
                    <!-- Left: Chart -->
                    <div class="w-full md:w-1/2 p-8">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Répartition Budgétaire Global</h3>
                        <div class="w-full max-w-xs mx-auto">
                            <canvas id="budgetChart"></canvas>
                        </div>
                    </div>

                    <!-- Right: Summary info -->
                    <div class="w-full md:w-1/2 p-8 flex flex-col justify-center space-y-6">
                        <h3 class="text-lg font-bold text-slate-900">Synthèse Financière</h3>
                        <div class="space-y-4">
                            @php
                                $budgetActif = \App\Models\Budget::latest()->first();
                                $totalEngage = \App\Models\Engagement::where('statut', 'approuve')->sum('total_ttc');
                                $totalAttente = \App\Models\Engagement::where('statut', 'en_attente')->sum('total_ttc');
                            @endphp
                            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                <span class="text-sm text-slate-500 font-medium">Budget annuel total</span>
                                <span class="text-sm font-bold text-slate-900">{{ $budgetActif ? number_format($budgetActif->total, 0, ',', ' ') . ' DH' : '—' }}</span>
                            </div>
                             <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                <span class="text-sm text-slate-500 font-medium">Total déduit (EB approuvés)</span>
                                <span class="text-sm font-bold text-emerald-600">{{ number_format($totalEngage, 0, ',', ' ') }} DH</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                <span class="text-sm text-slate-500 font-medium">Total en attente</span>
                                <span class="text-sm font-bold text-amber-500">{{ number_format($totalAttente, 0, ',', ' ') }} DH</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                <span class="text-sm text-slate-500 font-medium">Émetteurs actifs</span>
                                <span class="text-sm font-bold text-slate-900">{{ $stats['emetteurs_count'] }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500 font-medium">Propositions en attente</span>
                                <span class="text-sm font-bold text-indigo-600">{{ $stats['propositions_attente'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('budgetChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($chartData->pluck('label')) !!},
                        datasets: [{
                            data: {!! json_encode($chartData->pluck('value')) !!},
                            backgroundColor: ['#4f46e5', '#10b981', '#f43f5e', '#8b5cf6', '#0ea5e9', '#f59e0b'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) label += ': ';
                                        label += new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(context.raw);
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });

            }
        });
    </script>
    @endpush
</x-app-layout>
