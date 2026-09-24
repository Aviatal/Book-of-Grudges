@props(['title', 'subtitle' => null])

{{-- Ramka ze złotymi narożnikami — ten sam układ co w login.blade.php / register.blade.php --}}
<div class="flex items-center justify-center px-8 py-16">
    <div class="relative w-full max-w-[440px] p-1" style="border: 1px solid var(--border-frame); background: var(--bg-inset-alt)">
        <span class="absolute -top-px -left-px w-2.5 h-2.5" style="border-top: 2px solid var(--gold); border-left: 2px solid var(--gold)"></span>
        <span class="absolute -top-px -right-px w-2.5 h-2.5" style="border-top: 2px solid var(--gold); border-right: 2px solid var(--gold)"></span>
        <span class="absolute -bottom-px -left-px w-2.5 h-2.5" style="border-bottom: 2px solid var(--gold); border-left: 2px solid var(--gold)"></span>
        <span class="absolute -bottom-px -right-px w-2.5 h-2.5" style="border-bottom: 2px solid var(--gold); border-right: 2px solid var(--gold)"></span>

        <div class="text-center px-8 pt-9 pb-7.5" style="border: 1px solid var(--border-subtle); background: var(--bg-panel-gradient)">
            <div class="w-20 h-20 mx-auto mb-4 rounded-lg overflow-hidden" style="box-shadow: 0 4px 14px rgba(0,0,0,.7)">
                <img src="{{ asset('images/logo-mark.png') }}" alt="Book of Grudges" class="w-full h-full object-cover">
            </div>
            <h1 class="font-heading text-[22px] font-bold tracking-[.14em] m-0" style="color: var(--gold)">{{ $title }}</h1>
            @if($subtitle)
                <div class="italic my-4" style="color: var(--text-faint)">{{ $subtitle }}</div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
