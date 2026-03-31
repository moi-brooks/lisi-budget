@props(['status'])

@php
    $classes = match($status) {
        'approuve', 'actif' => 'bg-green-100 text-green-800 border-green-200',
        'en_attente', 'brouillon' => 'bg-orange-100 text-orange-800 border-orange-200',
        'rejete', 'clos', 'annule' => 'bg-red-100 text-red-800 border-red-200',
        default => 'bg-gray-100 text-gray-800 border-gray-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-0.5 rounded-full text-xs font-bold uppercase border shadow-sm $classes"]) }}>
    {{ str_replace('_', ' ', $status) }}
</span>
