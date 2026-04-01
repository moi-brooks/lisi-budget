<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-accent/10 rounded-2xl">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.15em] text-primary-muted">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-accent transition-colors">Admin</a></li>
                        <li><svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-primary font-black">Émetteurs</li>
                    </ol>
                </nav>
                <h2 class="font-display text-2xl font-extrabold text-primary leading-tight">
                    Gestion des Responsables
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-status-approved-bg border-0 rounded-2xl text-status-approved text-sm font-bold animate-in fade-in slide-in-from-top-2 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('admin.emetteurs.create') }}" 
                    class="group flex items-center space-x-3 px-8 py-4 bg-primary hover:bg-black hover:scale-105 active:scale-95 text-white rounded-[24px] font-bold shadow-premium transition-all duration-300">
                    <svg class="w-5 h-5 transform group-hover:rotate-10 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-sm tracking-tight font-black uppercase">Habiliter un Nouvel Émetteur</span>
                </a>
            </div>

            <x-table-card title="Liste des Émetteurs Habilités" search="true">
                <thead>
                    <tr class="bg-surface-50/50 uppercase text-[10px] text-primary-muted font-black tracking-[0.15em] border-b border-surface-100">
                        <th class="px-8 py-5 text-left italic">Utilisateur / Contact</th>
                        <th class="px-8 py-5 text-left">Profession / Rôle</th>
                        <th class="px-8 py-5 text-left">Budget Affecté</th>
                        <th class="px-8 py-5 text-right">Dotation Initiale</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @forelse($emetteurs as $emetteur)
                        <tr class="group hover:bg-surface-50/50 transition-colors duration-300">
                            <td class="px-8 py-6">
                                <div class="font-display font-extrabold text-primary group-hover:text-accent transition-colors leading-none tracking-tight">{{ $emetteur->user->name }}</div>
                                <div class="text-[11px] text-primary-muted font-bold lowercase tracking-tight mt-1">{{ $emetteur->user->email }}</div>
                            </td>
                            <td class="px-8 py-6 text-primary text-sm font-bold italic opacity-80 leading-none">
                                {{ $emetteur->profession ?? '-' }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="bg-surface-100 px-3 py-1.5 rounded-xl text-[10px] font-black text-primary-muted uppercase tracking-widest leading-none">
                                    {{ $emetteur->budget->saison }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-display font-black text-primary text-lg leading-none italic tracking-tighter">
                                        {{ number_format($emetteur->dotation, 0, ',', ' ') }}
                                    </span>
                                    <span class="text-[10px] font-black text-primary-muted uppercase tracking-widest mt-1 italic leading-none">DH</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-6">
                                    <a href="{{ route('admin.emetteurs.edit', $emetteur) }}" 
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-accent hover:text-blue-700 transition-all duration-300">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.emetteurs.destroy', $emetteur) }}" method="POST" onsubmit="return confirm('Cette action révoquera les accès de cet utilisateur.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black uppercase tracking-[0.2em] text-status-rejected hover:text-red-700 transition-all duration-300">
                                            Résilier
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-primary-muted italic text-sm font-black uppercase tracking-widest opacity-30">
                                Aucun responsable habilité.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table-card>
        </div>
    </div>
</x-app-layout>
