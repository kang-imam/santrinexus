@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('header');
@endphp
<nav {{ $attributes->class(['flex min-w-0 items-center justify-center']) }} aria-label="Navigasi utama">
    <div
        class="flex min-w-0 items-center gap-1 rounded-2xl border border-border/60 bg-surface/40 p-1 shadow-sm shadow-black/10">
        @forelse ($navigation as $item)
            @if (!empty($item['children']))
                <x-navigations.header.partials.dropdown-menu :item="$item" />
            @elseif (!empty($item['url']))
                <x-navigations.header.partials.menu-item :item="$item" variant="desktop" />
            @endif
        @empty
            @if (config('navigation.settings.show_empty_state', false))
                <span class="px-4 py-2 text-xs font-medium text-muted">
                    Navigasi belum tersedia.
                </span>
            @endif
        @endforelse
    </div>
</nav>
