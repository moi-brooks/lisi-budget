<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-8 py-4 bg-status-rejected hover:bg-rose-700 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-lg shadow-rose-500/20 transition-all duration-300 active:scale-95']) }}>
    {{ $slot }}
</button>
