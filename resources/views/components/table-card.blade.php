@props(['title' => null, 'action' => null, 'search' => false])

<div x-data="{ 
    searchQuery: '',
    filterTable() {
        let rows = this.$refs.tableBody.querySelectorAll('tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(this.searchQuery.toLowerCase()) ? '' : 'none';
        });
    }
}" 
@global-search.window="searchQuery = $event.detail; filterTable()"
class="bg-white border-0 rounded-[32px] overflow-hidden shadow-premium transition-all duration-300">
    @if($title || $action || $search)
        <div class="px-10 py-6 border-b border-surface-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-6">
                <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] italic">{{ $title }}</h3>
                @if($search)
                    <div class="relative group">
                        <input 
                            x-model="searchQuery" 
                            @input="filterTable()"
                            type="text" 
                            placeholder="Filtrer les données..." 
                            class="bg-surface-50 border-0 rounded-2xl px-5 py-2 text-xs font-medium text-primary placeholder:text-primary-muted w-64 focus:ring-2 focus:ring-accent/20 transition-all group-hover:bg-surface-100"
                        >
                        <svg class="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 text-primary-muted pointer-events-none opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" stroke-linecap="round"/></svg>
                    </div>
                @endif
            </div>
            @if($action)
                <div class="flex items-center space-x-3">{{ $action }}</div>
            @endif
        </div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <tbody x-ref="tableBody">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
