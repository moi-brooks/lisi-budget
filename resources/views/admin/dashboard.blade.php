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
                    <div class="text-slate-500 text-xs font-semibold tracking-wider uppercase mb-1">Engagements en attente</div>
                    <div class="text-3xl font-extrabold text-slate-900">{{ $stats['engagements_attente'] }}</div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Aperçu Global du Budget</h3>
                <div class="w-full md:w-1/2 mx-auto">
                    <canvas id="budgetChart"></canvas>
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
