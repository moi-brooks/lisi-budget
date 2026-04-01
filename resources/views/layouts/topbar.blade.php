<header class="h-20 bg-white/80 backdrop-blur-xl border-b border-surface-200/50 flex items-center justify-between px-10 flex-shrink-0 sticky top-0 z-40">
    <div class="flex items-center space-x-4">
        <!-- Dashboard Breadcrumb Reference -->
        <div class="hidden md:flex items-center space-x-2 text-primary-muted">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] italic opacity-40">Système Budgétaire</span>
        </div>
    </div>

    <div class="flex items-center space-x-8">
        <!-- Date / Period Display -->
        <div class="hidden lg:flex flex-col items-end mr-4">
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-primary-muted italic mb-0.5">Session actuelle</span>
            <span class="text-sm font-black text-primary tracking-tight leading-none italic">{{ date('F Y') }}</span>
        </div>

        <div class="h-8 w-px bg-surface-200"></div>

        <div class="flex items-center space-x-4 group cursor-pointer px-4 py-2 hover:bg-surface-50 rounded-2xl transition-all">
            <div class="flex flex-col items-end">
                <span class="text-xs font-black text-primary tracking-tight leading-none italic">{{ Auth::user()->name }}</span>
                <span class="text-[9px] font-black uppercase tracking-widest text-accent mt-0.5">{{ Auth::user()->role }}</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-surface-100 border border-surface-200/50 flex items-center justify-center text-primary font-black text-xs uppercase shadow-sm group-hover:scale-105 transition-transform italic">
                {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strrchr(Auth::user()->name, ' '), 1, 1) ?: '' }}
            </div>
        </div>
    </div>
</header>
