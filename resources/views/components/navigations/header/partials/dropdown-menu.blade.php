@props(['item' => [], 'id' => null])
@php
    $item = is_array($item) ? $item : [];
    $label = is_string($item['label'] ?? null) && trim($item['label']) !== '' ? trim($item['label']) : 'Menu';
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
    $isActive =
        (bool) ($item['active'] ?? false) ||
        collect($children)->contains(static fn($child): bool => (bool) ($child['active'] ?? false));
    $showIcons = (bool) config('navigation.settings.show_icons', true);
    $showActiveState = (bool) config('navigation.settings.show_active_state', true);
    $icon = $item['icon'] ?? null;
    $fallbackIcon = $item['icon_fallback'] ?? 'fa-solid fa-link';
    $id = is_string($id) && trim($id) !== '' ? trim($id) : 'header-dropdown-' . \Illuminate\Support\Str::uuid();
    $triggerClasses =
        'group inline-flex min-h-10 items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold transition-colors duration-200 ease-out focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary';
    $stateClasses =
        $isActive && $showActiveState
            ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5'
            : 'text-muted hover:bg-surface-hover hover:text-foreground';
    $hasIcon = is_string($icon) && trim($icon) !== '';
    $hasFallbackIcon = is_string($fallbackIcon) && trim($fallbackIcon) !== '';
@endphp
@if ($children !== [])
    <div x-data="{
        open: false,
        hoverEnabled() {
            return window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        },
        close(restoreFocus = false) {
            this.open = false;
            if (restoreFocus) this.$nextTick(() => this.$refs.trigger?.focus());
        },
        openMenu(position = 'first') {
            this.open = true;
            this.$nextTick(() => {
                const items = this.$refs.panel?.querySelectorAll('a[href]:not([aria-disabled=true])');
                if (!items?.length) return;
                (position === 'last' ? items[items.length - 1] : items[0]).focus();
            });
        },
        toggle() {
            this.open ? this.close() : this.open = true;
        },
        moveFocus(direction) {
            const items = this.$refs.panel?.querySelectorAll('a[href]:not([aria-disabled=true])');
            if (!items?.length) return;
            const currentIndex = Array.from(items).indexOf(document.activeElement);
            const nextIndex = currentIndex < 0 ? 0 : (currentIndex + direction + items.length) % items.length;
            items[nextIndex].focus();
        },
        handleKeydown(event) {
            if (event.key === 'Escape' && this.open) {
                event.preventDefault();
                event.stopPropagation();
                this.close(true);
            }
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.open ? this.moveFocus(1) : this.openMenu('first');
            }
            if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.open ? this.moveFocus(-1) : this.openMenu('last');
            }
            if (event.key === 'Home' && this.open) {
                event.preventDefault();
                this.openMenu('first');
            }
            if (event.key === 'End' && this.open) {
                event.preventDefault();
                this.openMenu('last');
            }
        }
    }" @mouseenter="if (hoverEnabled()) open = true"
        @mouseleave="if (hoverEnabled() && !$el.contains(document.activeElement)) close()" @click.outside="close()"
        @focusout="if (!$event.relatedTarget || !$el.contains($event.relatedTarget)) close()"
        @keydown="handleKeydown($event)" class="relative">
        <button x-ref="trigger" type="button" @click="toggle()" :aria-expanded="open.toString()"
            aria-controls="{{ $id }}" class="{{ $triggerClasses }} {{ $stateClasses }}">
            @if ($showIcons && ($hasIcon || $hasFallbackIcon))
                <x-shared.icon :name="$icon" :fallback="$fallbackIcon"
                    class="size-4 shrink-0 transition-transform duration-200 group-hover:scale-110"
                    aria-hidden="true" />
            @endif
            <span>{{ $label }}</span>
            <x-shared.icon name="heroicon-o-chevron-down" fallback="fa-solid fa-chevron-down"
                class="size-4 shrink-0 text-subtle transition-all duration-200 group-hover:text-primary"
                ::class="{ 'rotate-180 text-primary': open }" aria-hidden="true" />
        </button>
        <div id="{{ $id }}" x-ref="panel" x-cloak x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-2 scale-[0.98]"
            @click="if ($event.target.closest('a[href]')) close()" @keydown.arrow-down.prevent="moveFocus(1)"
            @keydown.arrow-up.prevent="moveFocus(-1)" @keydown.home.prevent="openMenu('first')"
            @keydown.end.prevent="openMenu('last')" :aria-hidden="(!open).toString()" :inert="!open"
            class="absolute left-0 top-full z-50 w-64 max-w-[calc(100vw-2rem)] origin-top-left pt-3 sm:max-w-xs">
            <div
                class="overflow-hidden rounded-2xl border border-border/80 bg-surface/95 p-2 shadow-2xl shadow-black/30 ring-1 ring-white/5 backdrop-blur-2xl">
                <nav aria-label="Submenu {{ $label }}" class="space-y-1">
                    @foreach ($children as $child)
                        <x-navigations.header.partials.menu-item :item="$child" variant="dropdown" />
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
@endif
