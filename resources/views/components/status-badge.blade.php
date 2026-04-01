@props(['status'])

@php
    $config = [
        'en_attente' => ['bg' => 'bg-amber-50/50', 'text' => 'text-amber-800', 'label' => 'En attente'],
        'approuve'   => ['bg' => 'bg-emerald-50/50', 'text' => 'text-emerald-800', 'label' => 'Approuvé'],
        'rejete'     => ['bg' => 'bg-rose-50/50', 'text' => 'text-rose-800', 'label' => 'Rejeté'],
        'actif'      => ['bg' => 'bg-emerald-50/50', 'text' => 'text-emerald-800', 'label' => 'Actif'],
        'termine'    => ['bg' => 'bg-slate-100/50', 'text' => 'text-slate-600', 'label' => 'Terminé'],
    ];
    $c = $config[$status] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'label' => str_replace('_', ' ', $status)];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.1em] italic {$c['bg']} {$c['text']} leading-none"]) }}>
    {{ $c['label'] }}
</span>
