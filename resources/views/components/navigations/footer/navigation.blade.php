@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('footer');
@endphp
<div class="grid grid-cols-2 gap-8">
    @foreach ($navigation as $group)
        <div>
            <h3 class="mb-6 text-sm font-bold text-foreground">{{ $group['label'] }}</h3>
            <ul class="space-y-3">
                @foreach ($group['items'] as $item)
                    <li>
                        <x-navigations.footer.partials.navigation-link :href="$item['url']" :label="$item['label']"
                            :icon="$item['icon']" />
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
