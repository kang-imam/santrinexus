@props([
    'title' => 'Sesuatu yang Baru Sedang Disiapkan',
    'description' =>
        'Kami sedang mengembangkan sesuatu yang lebih baik untuk meningkatkan pengalamanmu. Nantikan kehadirannya dalam waktu dekat.',
    'eyebrow' => 'UNDER DEVELOPMENT',
    'status' => 'Dalam Pengembangan',
    'icon' => 'heroicon-o-cube-transparent',
    'iconFallback' => 'fa-solid fa-cubes',
    'iconClass' => 'size-10',
    'actionUrl' => null,
    'actionLabel' => 'Kembali',
    'secondaryUrl' => null,
    'secondaryLabel' => 'Jelajahi Fitur Lain',
])
@php
    $soonId = 'soon-' . \Illuminate\Support\Str::uuid();
    $companyName = config('company.brand.name', config('company.name', config('app.name', 'SantriNexus')));
    $title = is_scalar($title) ? trim((string) $title) : '';
    $description = is_scalar($description) ? trim((string) $description) : '';
    $eyebrow = is_scalar($eyebrow) ? trim((string) $eyebrow) : '';
    $status = is_scalar($status) ? trim((string) $status) : '';
    $actionLabel = is_scalar($actionLabel) ? trim((string) $actionLabel) : '';
    $secondaryLabel = is_scalar($secondaryLabel) ? trim((string) $secondaryLabel) : '';
    $icon = is_string($icon) && trim($icon) !== '' ? trim($icon) : 'heroicon-o-cube-transparent';
    $iconFallback = is_string($iconFallback) && trim($iconFallback) !== '' ? trim($iconFallback) : 'fa-solid fa-cubes';
    $iconClass = is_string($iconClass) && trim($iconClass) !== '' ? trim($iconClass) : 'size-10';
    $resolveActionUrl = static function ($url): ?string {
        if (!is_string($url)) {
            return null;
        }
        $url = trim($url);
        if ($url === '' || preg_match('/[\x00-\x20\x7F\\\\]/', $url) === 1 || str_starts_with($url, '//')) {
            return null;
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if ($scheme === '') {
            return $url;
        }
        if (!in_array($scheme, ['http', 'https'], true)) {
            return null;
        }
        $host = parse_url($url, PHP_URL_HOST);
        return is_string($host) && $host !== '' ? $url : null;
    };
    $actionUrl = $resolveActionUrl($actionUrl);
    $secondaryUrl = $resolveActionUrl($secondaryUrl);
    $hasAction = $actionUrl !== null && $actionLabel !== '';
    $hasSecondaryAction = $secondaryUrl !== null && $secondaryLabel !== '';
@endphp
<section aria-labelledby="{{ $soonId }}-title"
    class="relative left-1/2 isolate flex min-h-[min(850px,85dvh)] w-screen max-w-none -translate-x-1/2 flex-col items-center justify-center overflow-hidden px-4 py-16 text-center sm:min-h-[75dvh] sm:px-6 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="soon-ambient soon-ambient-one left-1/2 top-1/2 size-[24rem] -translate-x-1/2 -translate-y-1/2 sm:size-[36rem]">
        </div>
        <div class="soon-ambient soon-ambient-two -left-24 top-1/4 size-72 sm:size-[26rem]"></div>
        <div class="soon-ambient soon-ambient-three -bottom-24 -right-24 size-80 sm:size-[28rem]"></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(rgba(148,163,184,0.16)_0.7px,transparent_0.7px)] bg-[size:24px_24px] opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_80%)]">
        </div>
        <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-background/60 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-background/60 to-transparent"></div>
        <div class="absolute inset-x-0 top-1/2 h-px bg-gradient-to-r from-transparent via-primary/10 to-transparent">
        </div>
    </div>
    <div class="soon-reactor relative mb-9 flex size-48 shrink-0 items-center justify-center sm:mb-11 sm:size-60"
        aria-hidden="true">
        <div class="soon-orbit soon-orbit-one"></div>
        <div class="soon-orbit soon-orbit-two"></div>
        <div class="soon-orbit soon-orbit-three"></div>
        <div class="soon-orbit-core"></div>
        <div class="soon-orbit-dot soon-orbit-dot-one"><span></span></div>
        <div class="soon-orbit-dot soon-orbit-dot-two"><span></span></div>
        <div class="soon-orbit-dot soon-orbit-dot-three"><span></span></div>
        <div class="soon-particle soon-particle-one"></div>
        <div class="soon-particle soon-particle-two"></div>
        <div class="soon-particle soon-particle-three"></div>
        <div class="soon-particle soon-particle-four"></div>
        <div
            class="soon-energy-core relative flex size-28 items-center justify-center overflow-hidden rounded-[2rem] border border-cyan-300/30 bg-gradient-to-br from-surface-raised via-surface to-background ring-1 ring-white/[0.05] sm:size-32 sm:rounded-[2.25rem]">
            <div
                class="soon-core-inner absolute inset-2 rounded-[1.5rem] border border-white/[0.05] sm:rounded-[1.75rem]">
            </div>
            <div class="soon-core-scan absolute inset-0 overflow-hidden rounded-[2rem] sm:rounded-[2.25rem]">
                <span></span></div>
            <div class="soon-core-glow absolute inset-0 rounded-[2rem] sm:rounded-[2.25rem]"></div>
            <x-shared.icon :name="$icon" :fallback="$iconFallback" :class="$iconClass . ' soon-main-icon relative z-10 shrink-0 text-cyan-300'" />
        </div>
        <div
            class="soon-floating-card absolute -right-2 top-1/4 rounded-xl border border-violet-400/20 bg-surface-raised/90 p-2.5 text-violet-300 shadow-lg shadow-violet-950/20 backdrop-blur-md">
            <x-shared.icon name="heroicon-o-sparkles" fallback="fa-solid fa-wand-magic-sparkles" class="size-4" />
        </div>
        <div
            class="soon-floating-card soon-floating-card-alt absolute -bottom-1 left-0 rounded-xl border border-cyan-400/20 bg-surface-raised/90 p-2.5 text-cyan-300 shadow-lg shadow-cyan-950/20 backdrop-blur-md">
            <x-shared.icon name="heroicon-o-bolt" fallback="fa-solid fa-bolt" class="size-4" />
        </div>
    </div>
    <div class="relative z-10 mx-auto w-full max-w-2xl">
        @if ($eyebrow !== '')
            <div
                class="mb-5 inline-flex max-w-full items-center gap-2.5 rounded-full border border-primary/20 bg-primary/[0.07] px-4 py-2 shadow-[0_0_30px_rgba(34,211,238,0.06)]">
                <span class="relative flex size-2 shrink-0" aria-hidden="true">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-primary opacity-50"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-primary"></span>
                </span>
                <span
                    class="truncate text-[10px] font-bold tracking-[0.2em] text-cyan-bright sm:text-xs">{{ $eyebrow }}</span>
            </div>
        @endif
        <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.25em] text-subtle sm:text-xs">A little patience, a
            better experience</p>
        <h1 id="{{ $soonId }}-title"
            class="text-balance text-3xl font-extrabold leading-tight tracking-tight text-foreground sm:text-4xl lg:text-5xl">
            {{ $title }}</h1>
        @if ($description !== '')
            <p class="mx-auto mt-5 max-w-xl text-pretty text-sm leading-7 text-muted sm:text-base sm:leading-8">
                {{ $description }}</p>
        @endif
    </div>
    @if ($status !== '')
        <div
            class="relative z-10 mt-8 inline-flex max-w-full items-center gap-3 rounded-2xl border border-border/80 bg-surface/70 px-4 py-3 shadow-lg shadow-black/10 backdrop-blur-xl sm:px-5">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-warning/10 text-warning"
                aria-hidden="true">
                <x-shared.icon name="heroicon-o-clock" fallback="fa-regular fa-clock" class="size-5" />
            </span>
            <div class="min-w-0 text-left">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-subtle">Current status</p>
                <p class="truncate text-sm font-semibold text-foreground">{{ $status }}</p>
            </div>
            <span class="ml-2 size-2 shrink-0 rounded-full bg-warning shadow-[0_0_10px_rgba(251,191,36,0.55)]"
                aria-hidden="true"></span>
        </div>
    @endif
    @if ($hasAction || $hasSecondaryAction)
        <div class="relative z-10 mt-8 flex w-full flex-col items-center justify-center gap-3 sm:w-auto sm:flex-row">
            @if ($hasAction)
                <a href="{{ $actionUrl }}"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-background shadow-[0_0_25px_rgba(34,211,238,0.16)] transition duration-200 hover:-translate-y-0.5 hover:bg-cyan-bright hover:shadow-[0_0_35px_rgba(34,211,238,0.25)] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                    <x-shared.icon name="heroicon-o-arrow-left" fallback="fa-solid fa-arrow-left" class="size-4"
                        aria-hidden="true" />
                    {{ $actionLabel }}
                </a>
            @endif
            @if ($hasSecondaryAction)
                <a href="{{ $secondaryUrl }}"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-border-bright bg-surface/70 px-6 py-3 text-sm font-semibold text-foreground transition duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-surface-hover hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                    {{ $secondaryLabel }}
                    <x-shared.icon name="heroicon-o-arrow-up-right" fallback="fa-solid fa-arrow-up-right-from-square"
                        class="size-4" aria-hidden="true" />
                </a>
            @endif
        </div>
    @endif
    <div class="relative z-10 mt-12 flex w-full max-w-md items-center gap-4 sm:mt-14" aria-hidden="true">
        <div class="h-px flex-1 bg-gradient-to-r from-transparent to-border"></div>
        <span class="whitespace-nowrap text-[9px] font-medium tracking-[0.2em] text-subtle sm:text-[10px]">BUILDING
            SOMETHING BETTER</span>
        <div class="h-px flex-1 bg-gradient-to-l from-transparent to-border"></div>
    </div>
    <p class="relative z-10 mt-5 text-xs text-subtle">Part of <span
            class="font-semibold text-muted">{{ $companyName }}</span></p>
</section>
