@props([
    'item' => [],
    'id' => null,
    'mode' => 'dropdown',
])
@php
    $item = is_array($item) ? $item : [];
    $label = is_string($item['label'] ?? null) && trim($item['label']) !== '' ? trim($item['label']) : 'Menu';
    $children = is_array($item['children'] ?? null)
        ? array_values(
            array_filter($item['children'], static function ($child): bool {
                return is_array($child) &&
                    filter_var($child['visible'] ?? true, FILTER_VALIDATE_BOOLEAN) &&
                    filter_var($child['navigable'] ?? true, FILTER_VALIDATE_BOOLEAN) &&
                    is_string($child['url'] ?? null) &&
                    trim($child['url']) !== '' &&
                    is_string($child['label'] ?? null) &&
                    trim($child['label']) !== '';
            }),
        )
        : [];
    $isActive =
        (bool) ($item['active'] ?? false) ||
        collect($children)->contains(static fn(array $child): bool => (bool) ($child['active'] ?? false));
    $showIcons = (bool) config('navigation.settings.show_icons', true);
    $showActiveState = (bool) config('navigation.settings.show_active_state', true);
    $icon = $item['icon'] ?? null;
    $fallbackIcon = $item['icon_fallback'] ?? 'fa-solid fa-link';
    $hasIcon = is_string($icon) && trim($icon) !== '';
    $hasFallbackIcon = is_string($fallbackIcon) && trim($fallbackIcon) !== '';
    $mode = is_string($mode) && in_array($mode, ['dropdown', 'inline'], true) ? $mode : 'dropdown';
    $id = is_string($id) && trim($id) !== '' ? trim($id) : 'header-dropdown-' . \Illuminate\Support\Str::uuid();
    $triggerClasses =
        $mode === 'inline'
            ? 'flex min-h-11 w-full min-w-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-left'
            : 'inline-flex min-h-10 min-w-0 items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold';
    $stateClasses =
        $isActive && $showActiveState
            ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5'
            : 'text-muted hover:bg-surface-hover hover:text-foreground';
    $panelClasses =
        $mode === 'inline'
            ? 'relative w-full pt-2'
            : 'absolute left-0 top-full z-50 w-64 max-w-[calc(100vw-2rem)] origin-top-left pt-3 sm:max-w-xs';
    $panelInnerClasses =
        $mode === 'inline'
            ? 'overflow-hidden rounded-xl border border-border/70 bg-background/50 p-1'
            : 'overflow-hidden rounded-2xl border border-border/80 bg-surface/95 shadow-xl shadow-black/15 backdrop-blur-xl';
@endphp
@if ($children !== [])
    <div x-data="{
        open: false,
        hoverEnabled() {
            return {{ $mode === 'dropdown' ? 'true' : 'false' }} && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        },
        getItems() {
            return Array.from(this.$refs.panel?.querySelectorAll('a[href]:not([aria-disabled=true]):not([tabindex=\'-1\'])') ?? [])
                .filter(item => item.getClientRects().length > 0 && !item.closest('[inert]'));
        },
        openMenu(position = 'first') {
            this.open = true;
            this.$nextTick(() => {
                const items = this.getItems();
                if (!items.length) return;
                (position === 'last' ? items[items.length - 1] : items[0]).focus();
            });
        },
        close(restoreFocus = false) {
            this.open = false;
            if (restoreFocus) this.$nextTick(() => this.$refs.trigger?.focus());
        },
        toggle() {
            this.open ? this.close() : this.open = true;
        },
        moveFocus(direction) {
            const items = this.getItems();
            if (!items.length) return;
            const current = items.indexOf(document.activeElement);
            const next = current < 0 ?
                (direction > 0 ? 0 : items.length - 1) :
                (current + direction + items.length) % items.length;
            items[next].focus();
        },
        handleKeydown(event) {
            if (event.key === 'Escape' && this.open) {
                event.preventDefault();
                event.stopPropagation();
                this.close(true);
                return;
            }
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.open ? this.moveFocus(1) : this.openMenu('first');
                return;
            }
            if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.open ? this.moveFocus(-1) : this.openMenu('last');
                return;
            }
            if (event.key === 'Home' && this.open) {
                event.preventDefault();
                this.openMenu('first');
                return;
            }
            if (event.key === 'End' && this.open) {
                event.preventDefault();
                this.openMenu('last');
            }
        }
    }" @mouseenter="if (hoverEnabled()) open = true"
        @mouseleave="if (hoverEnabled() && !$el.contains(document.activeElement)) close()" @click.outside="close()"
        @focusout="if (!$event.relatedTarget || !$el.contains($event.relatedTarget)) close()"
        @keydown="handleKeydown($event)" class="{{ $mode === 'inline' ? 'w-full' : 'relative' }}">
        <button x-ref="trigger" type="button" @click="if (hoverEnabled() && $event.detail > 0) open = true; else toggle()"
            :aria-expanded="open.toString()" aria-controls="{{ $id }}"
            class="group {{ $triggerClasses }} {{ $stateClasses }} transition-colors duration-200 ease-out focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
            @if ($showIcons && ($hasIcon || $hasFallbackIcon))
                <x-shared.icon :name="$icon" :fallback="$fallbackIcon"
                    class="size-4 shrink-0 transition-transform duration-200 group-hover:scale-110"
                    aria-hidden="true" />
            @endif
            <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
            <x-shared.icon name="heroicon-o-chevron-down" fallback="fa-solid fa-chevron-down"
                class="size-4 shrink-0 text-subtle transition-all duration-200 group-hover:text-primary"
                ::class="{ 'rotate-180 text-primary': open }" aria-hidden="true" />
        </button>
        <div id="{{ $id }}" x-ref="panel" x-cloak x-show="open"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
            :aria-hidden="(!open).toString()" :inert="!open"
            @click="if ($event.target.closest('a[href]')) close()" class="{{ $panelClasses }}">
            <div class="{{ $panelInnerClasses }}">
                <nav aria-label="Submenu {{ $label }}" class="space-y-1">
                    @foreach ($children as $child)
                        <x-navigations.header.partials.menu-item :item="$child" variant="dropdown" class="w-full" />
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
@endif
