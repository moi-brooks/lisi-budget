<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center border-l-4 border-blue-500">
                    <div>
                        <div class="text-gray-500 text-sm font-semibold uppercase">Budgets Actifs</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['budgets_count'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center border-l-4 border-green-500">
                    <div>
                        <div class="text-gray-500 text-sm font-semibold uppercase">Émetteurs</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['emetteurs_count'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center border-l-4 border-yellow-500">
                    <div>
                        <div class="text-gray-500 text-sm font-semibold uppercase">Propositions en attente</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['propositions_attente'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center border-l-4 border-purple-500">
                    <div>
                        <div class="text-gray-500 text-sm font-semibold uppercase">Engagements en attente</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['engagements_attente'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aperçu Global</h3>
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
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                            borderWidth: 0
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
