@props([
    'group' => [],
    'level' => 0,
])
@php
    $label = isset($group['label']) && is_scalar($group['label']) ? trim((string) $group['label']) : null;
    $rawItems = $group['items'] ?? ($group['children'] ?? []);
    $items = is_array($rawItems) ? array_values(array_filter($rawItems, 'is_array')) : [];
    $level = max(0, min(10, (int) $level));
    $visibleItems = collect($items)
        ->filter(function ($item) {
            if (array_key_exists('visible', $item) && !$item['visible']) {
                return false;
            }
            $hasLabel = isset($item['label']) && is_scalar($item['label']) && trim((string) $item['label']) !== '';
            $hasUrl = isset($item['url']) && is_string($item['url']) && trim($item['url']) !== '';
            $hasChildren = isset($item['children']) && is_array($item['children']) && $item['children'] !== [];
            return $hasLabel && ($hasUrl || $hasChildren);
        })
        ->values();
@endphp
@if ($visibleItems->isNotEmpty())
    <section class="min-w-0" @if ($level > 0 && $label !== null && $label !== '') aria-label="{{ $label }}" @endif>
        @if ($label !== null && $label !== '')
            <h3 @class([
                'flex items-center font-bold text-foreground',
                'mb-6 gap-3 text-sm tracking-wide' => $level === 0,
                'mb-3 gap-2 text-xs font-semibold tracking-wider text-muted' => $level > 0,
            ])>
                @if ($level === 0)
                    <span class="relative flex h-5 w-1 shrink-0 overflow-hidden rounded-full bg-primary"></span>
                    <span>{{ $label }}</span>
                    <span class="h-px min-w-3 flex-1 bg-linear-to-r from-primary/25 to-transparent"></span>
                @else
                    <span class="h-1 w-1 shrink-0 rounded-full bg-primary/70"></span>
                    <span>{{ $label }}</span>
                @endif
            </h3>
        @endif
        <ul @class([
            'space-y-3.5' => $level === 0,
            'space-y-2.5 border-l border-white/[0.08] pl-4' => $level > 0,
        ])>
            @foreach ($visibleItems as $item)
                @php
                    $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : [];
                    $hasChildren = $children !== [] && $level < 10;
                @endphp
                <li class="min-w-0">
                    <x-navigations.footer.partials.navigation-link :href="$item['url'] ?? null" :label="$item['label']" :icon="$item['icon'] ?? null"
                        :active="$item['active'] ?? false" :target="$item['target'] ?? null" :rel="$item['rel'] ?? null" :badge="$item['badge'] ?? null" />
                    @if ($hasChildren)
                        <div class="mt-3 pl-1">
                            <x-navigations.footer.partials.navigation-group :group="['items' => $children]" :level="$level + 1" />
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
@endif
