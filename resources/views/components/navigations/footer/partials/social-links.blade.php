@php
    $socials = [
        ['name' => 'Instagram', 'url' => config('company.socials.instagram'), 'icon' => 'fab-instagram'],
        ['name' => 'Facebook', 'url' => config('company.socials.facebook'), 'icon' => 'fab-facebook'],
        ['name' => 'LinkedIn', 'url' => config('company.socials.linkedin'), 'icon' => 'fab-linkedin'],
        ['name' => 'YouTube', 'url' => config('company.socials.youtube'), 'icon' => 'fab-youtube'],
        ['name' => 'TikTok', 'url' => config('company.socials.tiktok'), 'icon' => 'fab-tiktok'],
        ['name' => 'GitHub', 'url' => config('company.socials.github'), 'icon' => 'fab-github'],
    ];
@endphp
<div class="flex flex-wrap items-center gap-2">
    @foreach ($socials as $social)
        @if ($social['url'])
            <a href="{{ $social['url'] }}" aria-label="{{ $social['name'] }}" title="{{ $social['name'] }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface-raised text-muted transition-all duration-200 hover:-translate-y-1 hover:border-primary/30 hover:bg-primary/10 hover:text-primary">
                <x-dynamic-component :component="$social['icon']" class="h-4 w-4" />
            </a>
        @endif
    @endforeach
</div>