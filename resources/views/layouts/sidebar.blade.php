<aside class="w-64 bg-primary h-screen flex-shrink-0 flex flex-col text-white shadow-2xl relative z-50">
    <!-- Sidebar Logo -->
    <div class="px-8 h-20 flex items-center border-b border-white/5">
        <span class="font-display text-xl font-black tracking-tight italic">
            LISI <span class="text-accent not-italic">.</span> <span class="font-medium opacity-40 uppercase text-[10px] tracking-[0.3em] ml-1 not-italic">Budget</span>
        </span>
    </div>

    <!-- Navigation Groups -->
    <nav class="flex-1 px-4 py-8 overflow-y-auto space-y-2 custom-scrollbar">
        @if(auth()->user()->role === 'admin')
            <div class="px-4 mb-4">
                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-white/30 italic">Pilotage</p>
            </div>
            <x-sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Console
            </x-sidebar-link>
            
            <div class="px-4 mt-8 mb-4">
                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-white/30 italic">Gestion</p>
            </div>
            <x-sidebar-link :href="route('admin.budgets.index')" :active="request()->routeIs('admin.budgets.*')">
                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Budgets
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.emetteurs.index')" :active="request()->routeIs('admin.emetteurs.*')">
                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 014.99-1.857M10 7a4 4 0 11-8 0 4 4 0 018 0zM20 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Émetteurs
            </x-sidebar-link>

            <div class="px-4 mt-8 mb-4">
                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-white/30 italic">Flux de Travail</p>
            </div>
            <x-sidebar-link :href="route('admin.propositions.index')" :active="request()->routeIs('admin.propositions.*')">
                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Propositions
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.engagements.index')" :active="request()->routeIs('admin.engagements.*')">
                <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Engagements
            </x-sidebar-link>
        @elseif(auth()->user()->role === 'emetteur')
            <div class="px-4 mb-4">
                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-white/30 italic">Espace Personnel</p>
            </div>
            <x-sidebar-link :href="route('emetteur.dashboard')" :active="request()->routeIs('emetteur.dashboard')">
                 <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                 Dashboard
            </x-sidebar-link>

            <div class="px-4 mt-8 mb-4">
                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-white/30 italic">Actions Financières</p>
            </div>
            <x-sidebar-link :href="route('emetteur.lignes.index')" :active="request()->routeIs('emetteur.lignes.*')">
                 <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2"></path></svg>
                 Répartitions
            </x-sidebar-link>

            <x-sidebar-link :href="route('emetteur.engagements.index')" :active="request()->routeIs('emetteur.engagements.*')">
                 <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                 Bons de Commande
            </x-sidebar-link>
        @endif
    </nav>

    <!-- Sidebar Bottom User Section -->
    <div class="px-6 py-8 border-t border-white/5 space-y-4">
        <a href="{{ route('profile.edit') }}" class="flex items-center group cursor-pointer p-2 -m-2 rounded-2xl hover:bg-white/5 transition-all">
            <div class="w-10 h-10 rounded-2xl bg-white/10 group-hover:bg-accent transition-colors flex items-center justify-center text-xs font-black mr-3 shadow-premium italic uppercase">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-black truncate tracking-tight italic">{{ auth()->user()->name }}</p>
                <div class="flex items-center space-x-1">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/30 italic">{{ auth()->user()->role }}</span>
                    <span class="w-1 h-1 bg-white/20 rounded-full"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-accent italic group-hover:underline">Gérer</span>
                </div>
            </div>
        </a>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-white/40 hover:text-white transition duration-300 group/logout">
                <svg class="w-4 h-4 mr-3 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
