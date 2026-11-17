@props([
    'group' => [],
    'level' => 0,
])
@php
    $group = is_array($group) ? $group : [];
    $label = isset($group['label']) && is_scalar($group['label']) ? trim((string) $group['label']) : '';
    $rawItems = $group['items'] ?? ($group['children'] ?? []);
    $level = max(0, min(10, (int) $level));
    $items = is_array($rawItems) ? array_values(array_filter($rawItems, 'is_array')) : [];
    $sectionId = $label !== '' ? 'footer-navigation-' . \Illuminate\Support\Str::uuid() : null;
@endphp
@if ($items !== [])
    <section @if ($sectionId) aria-labelledby="{{ $sectionId }}" @endif
        {{ $attributes->class(['min-w-0', 'space-y-0' => $level === 0, 'space-y-1' => $level > 0]) }}>
        @if ($label !== '')
            <h3 id="{{ $sectionId }}" @class([
                'flex items-center font-bold text-foreground',
                'mb-6 gap-3 text-sm tracking-wide' => $level === 0,
                'mb-3 gap-2 text-xs font-semibold tracking-wider text-muted' => $level > 0,
            ])>
                @if ($level === 0)
                    <span class="relative flex h-5 w-1 shrink-0 overflow-hidden rounded-full bg-primary"
                        aria-hidden="true"></span>
                    <span>{{ $label }}</span>
                    <span class="h-px min-w-3 flex-1 bg-linear-to-r from-primary/25 to-transparent"
                        aria-hidden="true"></span>
                @else
                    <span class="h-1 w-1 shrink-0 rounded-full bg-primary/70" aria-hidden="true"></span>
                    <span>{{ $label }}</span>
                @endif
            </h3>
        @endif
        <ul @class([
            'space-y-3.5' => $level === 0,
            'space-y-2.5 border-l border-white/[0.08] pl-4' => $level > 0,
        ])>
            @foreach ($items as $item)
                @php
                    $itemLabel =
                        isset($item['label']) && is_scalar($item['label']) ? trim((string) $item['label']) : '';
                    $itemUrl = isset($item['url']) && is_string($item['url']) ? trim($item['url']) : '';
                    $isNavigable =
                        (bool) ($item['navigable'] ?? false) &&
                        (bool) ($item['parameters_resolved'] ?? false) &&
                        in_array(strtoupper((string) ($item['method'] ?? 'GET')), ['GET', 'HEAD'], true) &&
                        $itemUrl !== '';
                    $itemActive = (bool) ($item['active'] ?? false);
                    $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : [];
                    $hasChildren = $children !== [] && $level < 10;
                @endphp
                @if ($itemLabel !== '')
                    <li class="min-w-0">
                        <x-navigations.footer.partials.navigation-link :href="$isNavigable ? $itemUrl : null" :label="$itemLabel"
                            :icon="$item['icon'] ?? null" :icon-fallback="$item['icon_fallback'] ?? null" :active="$itemActive" :target="$item['target'] ?? null" :rel="$item['rel'] ?? null"
                            :badge="$item['badge'] ?? null" :description="$item['description'] ?? null" />
                        @if ($hasChildren)
                            <div class="mt-3 pl-1">
                                <x-navigations.footer.partials.navigation-group :group="['items' => $children]" :level="$level + 1" />
                            </div>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </section>
@endif
