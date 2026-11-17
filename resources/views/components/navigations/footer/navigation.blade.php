@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->build('footer');
    $navigation = is_array($navigation) ? $navigation : [];
    $navigation = array_values(
        array_filter(
            array_map(static function ($group) {
                if (!is_array($group)) {
                    return null;
                }
                $label = isset($group['label']) && is_scalar($group['label']) ? trim((string) $group['label']) : '';
                if ($label === '') {
                    return null;
                }
                $items = $group['items'] ?? ($group['children'] ?? []);
                if (!is_array($items) || $items === []) {
                    return null;
                }
                $group['label'] = $label;
                $group['items'] = $items;
                return $group;
            }, $navigation),
        ),
    );
@endphp
@if ($navigation !== [])
    <nav aria-label="Navigasi footer" class="w-full min-w-0">
        <div
            class="grid grid-cols-1 gap-x-10 gap-y-10 sm:grid-cols-2 sm:gap-x-8 sm:gap-y-12 lg:grid-cols-4 lg:gap-x-10 lg:gap-y-12">
            @foreach ($navigation as $group)
                <div class="min-w-0">
                    <x-navigations.footer.partials.navigation-group :group="$group" />
                </div>
            @endforeach
        </div>
    </nav>
@endif
