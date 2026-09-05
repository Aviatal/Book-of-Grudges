@props(['href'])
<a href="{{ $href }}" @click="mobileMenuOpen = false" class="sheet-subnav-link relative flex items-center gap-2.5 px-3 py-2 text-sm" style="color: var(--text-muted)">
    {{ $slot }}
</a>
