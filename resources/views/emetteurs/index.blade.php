<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Émetteurs</h2>
            <a href="{{ route('emetteurs.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ Nouvel émetteur</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Code</th>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Responsable</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($emetteurs as $emetteur)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $emetteur->code }}</td>
                        <td class="px-6 py-3"><a href="{{ route('emetteurs.show', $emetteur) }}" class="text-indigo-600 hover:underline font-medium">{{ $emetteur->nom }}</a></td>
                        <td class="px-6 py-3">{{ $emetteur->user->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('emetteurs.edit', $emetteur) }}" class="text-indigo-500 hover:underline text-xs">Modifier</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-6 text-center text-gray-400">Aucun émetteur.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $emetteurs->links() }}</div>
        </div>
    </div>
</x-app-layout>
