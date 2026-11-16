@props([
    'title' => 'Sesuatu yang Baru Sedang Disiapkan',
    'description' =>
        'Kami sedang mengembangkan sesuatu yang lebih baik untuk meningkatkan pengalamanmu. Nantikan kehadirannya dalam waktu dekat.',
    'eyebrow' => 'UNDER DEVELOPMENT',
    'status' => 'Dalam Pengembangan',
    'icon' => 'heroicon-o-cube-transparent',
    'iconClass' => 'size-10',
    'actionUrl' => null,
    'actionLabel' => 'Kembali',
    'secondaryUrl' => null,
    'secondaryLabel' => 'Jelajahi Fitur Lain',
])
<section
    class="relative isolate flex min-h-[560px] w-full flex-col items-center justify-center overflow-hidden px-2 py-12 text-center sm:px-6 sm:py-16"
    aria-labelledby="soon-title">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="soon-ambient soon-ambient-one absolute left-1/2 top-1/2 size-80 -translate-x-1/2 -translate-y-1/2 rounded-full sm:size-[30rem]">
        </div>
        <div class="soon-ambient soon-ambient-two absolute -left-20 top-1/4 size-48 rounded-full"></div>
        <div class="soon-ambient soon-ambient-three absolute -bottom-20 -right-20 size-56 rounded-full"></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(rgba(148,163,184,0.15)_0.7px,transparent_0.7px)] bg-[size:24px_24px] opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_75%)]">
        </div>
    </div>
    <div class="soon-reactor relative mb-9 flex size-48 items-center justify-center sm:mb-11 sm:size-60"
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
            class="soon-energy-core relative flex size-28 items-center justify-center rounded-[2rem] border border-cyan-300/30 bg-gradient-to-br from-surface-raised via-surface to-background ring-1 ring-white/[0.05] sm:size-32 sm:rounded-[2.25rem]">
            <div
                class="soon-core-inner absolute inset-2 rounded-[1.5rem] border border-white/[0.05] sm:rounded-[1.75rem]">
            </div>
            <div class="soon-core-scan absolute inset-0 overflow-hidden rounded-[2rem] sm:rounded-[2.25rem]">
                <span></span>
            </div>
            <div class="soon-core-glow absolute inset-0 rounded-[2rem] sm:rounded-[2.25rem]"></div>
            <x-dynamic-component :component="$icon"
                class="{{ $iconClass }} soon-main-icon relative z-10 text-cyan-300" />
        </div>
        <div
            class="soon-floating-card absolute -right-2 top-1/4 rounded-xl border border-violet-400/20 bg-surface-raised/90 p-2.5 text-violet-300 backdrop-blur-md">
            <x-heroicon-o-sparkles class="size-4" />
        </div>
        <div
            class="soon-floating-card soon-floating-card-alt absolute -bottom-1 left-0 rounded-xl border border-cyan-400/20 bg-surface-raised/90 p-2.5 text-cyan-300 backdrop-blur-md">
            <x-heroicon-o-bolt class="size-4" />
        </div>
    </div>
    <div class="mx-auto w-full max-w-2xl">
        <div
            class="mb-5 inline-flex items-center gap-2.5 rounded-full border border-primary/20 bg-primary/[0.07] px-4 py-2 shadow-[0_0_30px_rgba(34,211,238,0.06)]">
            <span class="relative flex size-2" aria-hidden="true">
                <span class="absolute inline-flex size-full animate-ping rounded-full bg-primary opacity-50"></span>
                <span class="relative inline-flex size-2 rounded-full bg-primary"></span>
            </span>
            <span class="text-[10px] font-bold tracking-[0.2em] text-cyan-bright sm:text-xs">{{ $eyebrow }}</span>
        </div>
        <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.25em] text-subtle sm:text-xs">A little patience, a
            better experience</p>
        <h1 id="soon-title"
            class="text-3xl font-extrabold leading-tight tracking-tight text-foreground sm:text-4xl lg:text-5xl">
            {{ $title }}</h1>
        <p class="mx-auto mt-5 max-w-xl text-sm leading-7 text-muted sm:text-base sm:leading-8">{{ $description }}</p>
    </div>
    <div
        class="mt-8 inline-flex max-w-full items-center gap-3 rounded-2xl border border-border/80 bg-surface/70 px-4 py-3 shadow-lg shadow-black/10 backdrop-blur-xl sm:px-5">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-warning/10 text-warning">
            <x-heroicon-o-clock class="size-5" />
        </span>
        <div class="min-w-0 text-left">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-subtle">Current status</p>
            <p class="truncate text-sm font-semibold text-foreground">{{ $status }}</p>
        </div>
        <span class="ml-2 size-2 shrink-0 rounded-full bg-warning shadow-[0_0_10px_rgba(251,191,36,0.55)]"
            aria-hidden="true"></span>
    </div>
    @if ($actionUrl || $secondaryUrl)
        <div class="mt-8 flex w-full flex-col items-center justify-center gap-3 sm:w-auto sm:flex-row">
            @if ($actionUrl)
                <a href="{{ $actionUrl }}"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-background shadow-[0_0_25px_rgba(34,211,238,0.16)] transition duration-200 hover:-translate-y-0.5 hover:bg-cyan-bright hover:shadow-[0_0_35px_rgba(34,211,238,0.25)] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                    <x-heroicon-o-arrow-left class="size-4" />
                    {{ $actionLabel }}
                </a>
            @endif
            @if ($secondaryUrl)
                <a href="{{ $secondaryUrl }}"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-border-bright bg-surface/70 px-6 py-3 text-sm font-semibold text-foreground transition duration-200 hover:border-primary/40 hover:bg-surface-hover hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                    {{ $secondaryLabel }}
                    <x-heroicon-o-arrow-up-right class="size-4" />
                </a>
            @endif
        </div>
    @endif
    <div class="mt-12 flex w-full max-w-md items-center gap-4 sm:mt-14" aria-hidden="true">
        <div class="h-px flex-1 bg-gradient-to-r from-transparent to-border"></div>
        <span class="whitespace-nowrap text-[9px] font-medium tracking-[0.2em] text-subtle sm:text-[10px]">BUILDING
            SOMETHING BETTER</span>
        <div class="h-px flex-1 bg-gradient-to-l from-transparent to-border"></div>
    </div>
    <p class="mt-5 text-xs text-subtle">Part of <span
            class="font-semibold text-muted">{{ config('app.name', 'SantriNexus') }}</span></p>
</section>
