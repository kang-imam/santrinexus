@props(['items' => []])
@php
    $items = is_array($items) ? $items : [];
    $visibleItems = array_values(
        array_filter($items, static function ($item) {
            if (!is_array($item) || ($item['visible'] ?? true) !== true) {
                return false;
            }
            $children = $item['children'] ?? [];
            $hasChildren = is_array($children) && count($children) > 0;
            $url = $item['url'] ?? null;
            $isNavigable = ($item['navigable'] ?? true) === true;
            $hasUrl = is_string($url) && trim($url) !== '';
            return $hasChildren || ($hasUrl && $isNavigable);
        }),
    );
@endphp
<nav aria-label="Navigasi utama" {{ $attributes->class(['flex min-w-0 items-center justify-center']) }}>
    <div
        class="flex min-w-0 items-center gap-1 rounded-2xl border border-border/60 bg-surface/40 p-1 shadow-sm shadow-black/10">
        @forelse ($visibleItems as $item)
            @php
                $children = $item['children'] ?? [];
                $hasChildren = is_array($children) && count($children) > 0;
            @endphp
            @if ($hasChildren)
                <x-navigations.header.partials.dropdown-menu :item="$item" />
            @else
                <x-navigations.header.partials.menu-item :item="$item" variant="desktop" />
            @endif
        @empty
            @if (config('navigation.settings.show_empty_state', false))
                <span class="px-4 py-2 text-xs font-medium text-muted" role="status">Navigasi belum tersedia.</span>
            @endif
        @endforelse
    </div>
</nav>
