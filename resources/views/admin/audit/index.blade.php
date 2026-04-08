<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historique des modifications (Audit Log)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 uppercase text-xs text-slate-500 font-semibold tracking-wider border-b border-slate-100">
                                <th class="p-4">Date</th>
                                <th class="p-4">Utilisateur</th>
                                <th class="p-4">Événement</th>
                                <th class="p-4">Ressource</th>
                                <th class="p-4">Détails (Ancienne → Nouvelle valeur)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($activities as $activity)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-4 text-sm text-slate-600 whitespace-nowrap">
                                        {{ $activity->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="p-4 text-sm font-medium text-slate-900">
                                        {{ $activity->causer ? $activity->causer->name : 'Système' }}
                                    </td>
                                    <td class="p-4 text-sm text-slate-600">
                                        @if($activity->event === 'created')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Création</span>
                                        @elseif($activity->event === 'updated')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Modification</span>
                                        @elseif($activity->event === 'deleted')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">Suppression</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">{{ $activity->event }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm text-slate-600">
                                        {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                    </td>
                                    <td class="p-4 text-sm text-slate-600">
                                        @if($activity->properties && $activity->event === 'updated')
                                            <div class="space-y-1">
                                                @foreach($activity->properties['attributes'] ?? [] as $key => $newValue)
                                                    @php $oldValue = $activity->properties['old'][$key] ?? 'N/A'; @endphp
                                                    <div class="flex items-center text-xs">
                                                        <span class="font-medium text-slate-700 w-24 truncate">{{ ucfirst($key) }}:</span>
                                                        <span class="bg-red-50 text-red-700 px-1.5 rounded">{{ is_array($oldValue) ? json_encode($oldValue) : $oldValue }}</span>
                                                        <svg class="h-3 w-3 text-slate-400 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                        <span class="bg-green-50 text-green-700 px-1.5 rounded">{{ is_array($newValue) ? json_encode($newValue) : $newValue }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">Aucun détail disponible</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 text-sm">
                                        Aucun historique n'est disponible
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($activities->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
