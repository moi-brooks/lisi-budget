@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[10px] font-black uppercase tracking-[0.2em] text-primary-muted ml-1 mb-2 italic']) }}>
    {{ $value ?? $slot }}
</label>
