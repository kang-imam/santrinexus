@props([
    'title' => 'Coming Soon',
    'description' =>
        'Halaman ini sedang dalam tahap pengembangan. Kami sedang mempersiapkan sesuatu yang menarik untuk Anda.',
    'label' => 'Under Development',
    'backUrl' => null,
    'backText' => 'Kembali',
    'showBack' => true,
])
@php
    $previousUrl = url()->previous();
    $backLink = $backUrl ?? ($previousUrl !== url()->current() ? $previousUrl : url('/'));
@endphp
<section
    class="relative isolate flex w-full items-center justify-center overflow-hidden rounded-2xl border border-border bg-surface px-5 py-16 sm:py-20"
    aria-labelledby="coming-soon-title">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-32 -top-32 h-72 w-72 rounded-full bg-primary/10 blur-[100px]"></div>
        <div class="absolute -bottom-32 -right-32 h-72 w-72 rounded-full bg-secondary/10 blur-[100px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(34,211,238,0.06)_0%,transparent_70%)]">
        </div>
        <div
            class="absolute inset-0 opacity-[0.025] bg-[linear-gradient(rgba(255,255,255,0.5)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.5)_1px,transparent_1px)] [background-size:36px_36px]">
        </div>
    </div>
    <div class="relative z-10 mx-auto flex w-full max-w-xl flex-col items-center text-center">
        <div class="relative mb-8 flex h-24 w-24 items-center justify-center" aria-hidden="true">
            <div class="absolute inset-0 animate-ping rounded-full bg-primary/10 motion-reduce:animate-none"></div>
            <div class="absolute inset-2 rounded-full border border-primary/20 bg-primary/5"></div>
            <div
                class="relative flex h-16 w-16 items-center justify-center rounded-2xl border border-primary/30 bg-surface-raised shadow-[0_0_35px_rgba(34,211,238,0.15)]">
                <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"></path>
                </svg>
            </div>
        </div>
        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-2">
            <span class="relative flex h-2 w-2" aria-hidden="true">
                <span
                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-75 motion-reduce:animate-none"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-accent"></span>
            </span>
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">{{ $label }}</span>
        </div>
        <h1 id="coming-soon-title" class="text-3xl font-extrabold tracking-tight text-foreground sm:text-5xl">
            {{ $title }}</h1>
        <div class="mt-5 h-1 w-16 rounded-full bg-linear-to-r from-primary to-accent" aria-hidden="true"></div>
        <p class="mt-6 max-w-md text-sm leading-7 text-muted sm:text-base">{{ $description }}</p>
        @if (isset($slot) && $slot->isNotEmpty())
            <div class="mt-8 w-full">
                {{ $slot }}
            </div>
        @endif
        @if ($showBack)
            <div class="mt-10">
                <a href="{{ $backLink }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-border-bright bg-surface-raised px-6 py-3 text-sm font-semibold text-foreground transition duration-300 hover:border-primary/50 hover:bg-surface-hover hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {{ $backText }}
                </a>
            </div>
        @endif
        <div class="mt-14 flex items-center gap-3 text-xs font-medium tracking-wider text-subtle" aria-hidden="true">
            <span class="h-px w-8 bg-border-bright"></span>
            <span>WORK IN PROGRESS</span>
            <span class="h-px w-8 bg-border-bright"></span>
        </div>
    </div>
</section>
