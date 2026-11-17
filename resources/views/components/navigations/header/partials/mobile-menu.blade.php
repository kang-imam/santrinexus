@props(['items' => []])
@php
    $navigation = is_array($items) ? $items : [];
    $showIcons = (bool) config('navigation.settings.show_icons', true);
    $showActiveState = (bool) config('navigation.settings.show_active_state', true);
    $visibleItems = array_values(
        array_filter($navigation, static function ($item): bool {
            if (!is_array($item) || ($item['visible'] ?? true) !== true) {
                return false;
            }
            $children = $item['children'] ?? [];
            $hasChildren = is_array($children) && count($children) > 0;
            $url = $item['url'] ?? null;
            return $hasChildren || (($item['navigable'] ?? true) === true && is_string($url) && trim($url) !== '');
        }),
    );
@endphp
<nav class="space-y-2" aria-label="Navigasi utama mobile">
    @forelse ($visibleItems as $item)
        @php
            $children = is_array($item['children'] ?? null)
                ? array_values(
                    array_filter($item['children'], static function ($child): bool {
                        return is_array($child) &&
                            ($child['visible'] ?? true) === true &&
                            ($child['navigable'] ?? true) === true &&
                            is_string($child['url'] ?? null) &&
                            trim($child['url']) !== '' &&
                            is_string($child['label'] ?? null) &&
                            trim($child['label']) !== '';
                    }),
                )
                : [];
            $hasChildren = $children !== [];
            $isActive =
                (bool) ($item['active'] ?? false) ||
                collect($children)->contains(static fn($child): bool => (bool) ($child['active'] ?? false));
            $label = is_string($item['label'] ?? null) && trim($item['label']) !== '' ? trim($item['label']) : 'Menu';
            $icon = $item['icon'] ?? null;
            $fallbackIcon = $item['icon_fallback'] ?? 'fa-solid fa-link';
            $submenuId = 'mobile-submenu-' . \Illuminate\Support\Str::uuid();
        @endphp
        @if ($hasChildren)
            <div x-data="{ open: @js($isActive), toggle() { this.open = !this.open; }, close() { this.open = false; }, moveFocus(direction) { const items = this.$refs.panel?.querySelectorAll('a[href]:not([aria-disabled=true])'); if (!items?.length) return; const current = Array.from(items).indexOf(document.activeElement); const next = current < 0 ? 0 : (current + direction + items.length) % items.length;
                    items[next].focus(); }, focusItem(position) { const items = this.$refs.panel?.querySelectorAll('a[href]:not([aria-disabled=true])'); if (!items?.length) return;
                    items[position === 'last' ? items.length - 1 : 0].focus(); } }" @keydown.escape.stop="close(); $refs.trigger.focus()"
                @keydown.arrow-down.prevent="if (open) moveFocus(1); else { open = true; $nextTick(() => focusItem('first')); }"
                @keydown.arrow-up.prevent="if (open) moveFocus(-1); else { open = true; $nextTick(() => focusItem('last')); }"
                @keydown.home.prevent="if (open) focusItem('first')" @keydown.end.prevent="if (open) focusItem('last')"
                class="overflow-hidden rounded-2xl border {{ $isActive && $showActiveState ? 'border-primary/25 bg-primary/4 shadow-sm shadow-primary/5' : 'border-border/80 bg-surface/70' }} transition-colors duration-200">
                <button x-ref="trigger" type="button" @click="toggle()" :aria-expanded="open.toString()"
                    aria-controls="{{ $submenuId }}"
                    class="group flex min-h-14 w-full items-center gap-3 px-3.5 py-3.5 text-left text-sm font-semibold transition-colors duration-200 hover:bg-surface-hover focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary {{ $isActive && $showActiveState ? 'text-primary' : 'text-foreground' }}">
                    @if ($showIcons)
                        <x-shared.icon :name="$icon" :fallback="$fallbackIcon"
                            class="flex size-9 shrink-0 items-center justify-center rounded-xl {{ $isActive && $showActiveState ? 'bg-primary/10 text-primary' : 'bg-surface-raised text-muted group-hover:text-primary' }}"
                            aria-hidden="true" />
                    @endif
                    <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
                    @if ($isActive && $showActiveState)
                        <span class="size-1.5 shrink-0 rounded-full bg-primary" aria-hidden="true"></span>
                    @endif
                    <x-shared.icon name="heroicon-o-chevron-down" fallback="fa-solid fa-chevron-down"
                        class="size-4 shrink-0 text-subtle transition-all duration-200 group-hover:text-primary"
                        ::class="{ 'rotate-180 text-primary': open }" aria-hidden="true" />
                </button>
                <div id="{{ $submenuId }}" x-ref="panel" x-cloak x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" :aria-hidden="(!open).toString()"
                    :inert="!open" @click="if ($event.target.closest('a[href]')) close()"
                    class="border-t border-border/70 bg-background/40 px-2 py-2">
                    <div class="space-y-1">
                        @foreach ($children as $child)
                            <x-navigations.header.partials.menu-item :item="$child" variant="mobile"
                                class="w-full" />
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div
                class="overflow-hidden rounded-2xl border {{ $isActive && $showActiveState ? 'border-primary/25 bg-primary/4 shadow-sm shadow-primary/5' : 'border-border/80 bg-surface/70' }} transition-colors duration-200">
                <x-navigations.header.partials.menu-item :item="$item" variant="mobile"
                    class="min-h-14 rounded-2xl px-3.5 py-3.5" />
            </div>
        @endif
    @empty
        @if (config('navigation.settings.show_empty_state', false))
            <div
                class="flex flex-col items-center justify-center gap-2 rounded-2xl border border-dashed border-border bg-surface/50 px-4 py-8 text-center">
                <span class="flex size-10 items-center justify-center rounded-xl bg-surface-raised text-muted">
                    <x-shared.icon name="heroicon-o-bars-3" fallback="fa-solid fa-bars" class="size-5"
                        aria-hidden="true" />
                </span>
                <p class="text-sm font-semibold text-foreground">Navigasi belum tersedia</p>
                <p class="text-xs text-muted">Silakan periksa kembali konfigurasi navigasi.</p>
            </div>
        @endif
    @endforelse
</nav>
