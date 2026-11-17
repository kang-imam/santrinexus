@props(['items' => []])
@php
    $navigation = is_array($items) ? $items : [];
    $visibleItems = array_values(
        array_filter($navigation, static function ($item): bool {
            if (!is_array($item) || !filter_var($item['visible'] ?? true, FILTER_VALIDATE_BOOLEAN)) {
                return false;
            }
            $children = $item['children'] ?? [];
            $hasChildren = is_array($children) && count($children) > 0;
            $url = $item['url'] ?? null;
            $isNavigable = filter_var($item['navigable'] ?? true, FILTER_VALIDATE_BOOLEAN);
            return $hasChildren || ($isNavigable && is_string($url) && trim($url) !== '');
        }),
    );
@endphp
<nav aria-label="Navigasi utama" {{ $attributes->class(['flex min-w-0 items-center justify-center overflow-visible']) }}>
    <div
        class="flex min-w-0 items-center justify-center gap-1 overflow-visible rounded-2xl border border-border/60 bg-surface/40 p-1 shadow-sm shadow-black/10">
        @forelse ($visibleItems as $item)
            @php
                $children = is_array($item['children'] ?? null)
                    ? array_values(
                        array_filter($item['children'], static function ($child): bool {
                            return is_array($child) &&
                                filter_var($child['visible'] ?? true, FILTER_VALIDATE_BOOLEAN) &&
                                ((is_array($child['children'] ?? null) && count($child['children']) > 0) ||
                                    (filter_var($child['navigable'] ?? true, FILTER_VALIDATE_BOOLEAN) &&
                                        is_string($child['url'] ?? null) &&
                                        trim($child['url']) !== ''));
                        }),
                    )
                    : [];
                $hasChildren = $children !== [];
            @endphp
            @if ($hasChildren)
                <x-navigations.header.partials.dropdown-menu :item="$item" />
            @else
                <x-navigations.header.partials.menu-item :item="$item" variant="desktop" />
            @endif
        @empty
            @if (config('navigation.settings.show_empty_state', false))
                <span class="px-4 py-2 text-xs font-medium text-muted" role="status">
                    Navigasi belum tersedia.
                </span>
            @endif
        @endforelse
    </div>
</nav>
