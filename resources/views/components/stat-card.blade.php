@props(['label', 'value', 'sub' => null])

<div class="bg-white border border-slate-200 rounded-xl p-5 transition-all duration-200 hover:border-slate-300">
    <div class="text-[12px] uppercase tracking-wider text-slate-500 font-medium mb-1">
        {{ $label }}
    </div>
    <div class="text-[28px] font-semibold text-navy leading-none">
        {{ $value }}
    </div>
    @if($sub)
        <div class="text-[12px] text-slate-400 mt-2 flex items-center">
            {{ $sub }}
        </div>
    @endif
</div>
