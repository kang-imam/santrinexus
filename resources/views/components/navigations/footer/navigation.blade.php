@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('footer');
    $navigation = is_array($navigation) ? $navigation : [];
    $navigation = array_values(
        array_filter($navigation, function ($group) {
            return is_array($group) &&
                isset($group['label']) &&
                is_string($group['label']) &&
                trim($group['label']) !== '' &&
                isset($group['items']) &&
                is_array($group['items']) &&
                $group['items'] !== [];
        }),
    );
@endphp
@if ($navigation !== [])
    <nav aria-label="Navigasi footer" class="w-full">
        <div
            class="grid grid-cols-1 gap-x-10 gap-y-12 sm:grid-cols-2 sm:gap-x-8 sm:gap-y-14 lg:grid-cols-4 lg:gap-x-10 lg:gap-y-12">
            @foreach ($navigation as $group)
                <div class="group min-w-0">
                    <x-navigations.footer.partials.navigation-group :group="$group" />
                </div>
            @endforeach
        </div>
    </nav>
@endif
