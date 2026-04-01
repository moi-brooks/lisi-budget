<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-8 py-4 bg-surface-100 hover:bg-surface-200 text-primary-muted text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all duration-300 active:scale-95']) }}>
    {{ $slot }}
</button>
