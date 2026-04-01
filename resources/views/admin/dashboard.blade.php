<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li class="text-primary font-black">Admin</li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary-muted italic">Console de Pilotage</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Tableau de Bord Institutionnel
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-stat-card 
                    label="Budgets Actifs" 
                    value="{{ $stats['budgets_count'] }}" 
                    sub="Année universitaire {{ date('Y') }}"
                />
                <x-stat-card 
                    label="Émetteurs" 
                    value="{{ $stats['emetteurs_count'] }}" 
                    sub="Acteurs habilités"
                />
                <x-stat-card 
                    label="Arbitrage" 
                    value="{{ $stats['propositions_attente'] }}" 
                    sub="Propositions en attente"
                />
                <x-stat-card 
                    label="Engagements" 
                    value="{{ $stats['engagements_attente'] }}" 
                    sub="BC en attente d'approbation"
                />
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Chart Section -->
                <div class="lg:col-span-2 bg-white border-0 rounded-[40px] p-12 shadow-premium">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="font-display text-xl font-black text-primary tracking-tight">Répartition Budgétaire Global</h3>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted mt-1 italic">Synthèse des dotations par saison</p>
                        </div>
                        <div class="p-2 bg-surface-50 rounded-xl">
                            <svg class="w-5 h-5 text-primary-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-width="1.5"/></svg>
                        </div>
                    </div>
                    <div class="relative min-h-[350px] flex items-center justify-center">
                        <canvas id="budgetChart" class="max-h-[350px]"></canvas>
                    </div>
                </div>

                <!-- Quick Actions / Info -->
                <div class="space-y-6">
                    <div class="bg-primary text-white rounded-[32px] p-8 shadow-premium overflow-hidden relative group">
                        <div class="absolute top-0 right-0 -m-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        <h4 class="font-display font-black text-lg mb-4 italic tracking-tight">Raccourcis Admin</h4>
                        <div class="space-y-3 relative">
                            <a href="{{ route('admin.propositions.index') }}" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-colors group/link">
                                <span class="text-xs font-black uppercase tracking-widest">Validations</span>
                                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <a href="{{ route('admin.budgets.create') }}" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-colors group/link">
                                <span class="text-xs font-black uppercase tracking-widest">Nouvel Exercice</span>
                                <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="bg-surface-50 border border-surface-200/50 rounded-[32px] p-8">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted mb-4 italic">Notes Système</h4>
                        <div class="flex items-start space-x-3">
                            <div class="w-1.5 h-1.5 mt-1.5 rounded-full bg-accent animate-pulse"></div>
                            <p class="text-xs font-bold text-primary italic leading-relaxed">
                                Les dotations non consommées en fin d'exercice seront automatiquement reportées ou clôturées selon l'arbitrage.
                            </p>
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
                            backgroundColor: ['#0F172A', '#2563EB', '#64748B', '#94A3B8', '#CBD5E1', '#E2E8F0'],
                            borderWidth: 8,
                            borderColor: '#ffffff',
                            hoverOffset: 12,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    padding: 30,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 11,
                                        weight: '600'
                                    },
                                    color: '#64748B'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#0F172A',
                                padding: 12,
                                titleFont: { size: 13, weight: 'bold' },
                                bodyFont: { size: 12 },
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
