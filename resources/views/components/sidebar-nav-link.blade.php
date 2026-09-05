@props(['route', 'pattern'])
<a href="{{ $route }}"
   class="relative flex items-center gap-2.5 px-3 py-2.5 text-base border border-transparent hover:bg-[#221c14] hover:border-[var(--border-default)]"
   style="color: var(--text-body)"
>
    @if(request()->routeIs($pattern))
        <span class="absolute left-0 top-1.5 bottom-1.5 w-[3px]" style="background: linear-gradient(var(--gold), var(--border-accent))"></span>
    @endif
    <span class="w-[7px] h-[7px] rotate-45 shrink-0" style="background: var(--border-accent-hover)"></span>
    {{ $slot }}
</a>
