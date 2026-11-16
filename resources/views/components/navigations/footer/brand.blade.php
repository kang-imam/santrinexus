<div class="max-w-sm">
    <a href="/" class="mb-6 flex items-center gap-3">
        <x-partials.brand />
    </a>
    <p class="text-sm leading-7 text-muted">
        {{ config('company.description') }}
    </p>
    <div class="mt-6 flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-2 rounded-full border border-primary/15 bg-primary/5 px-3 py-1.5 text-xs font-medium text-primary">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-50"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-accent"></span>
            </span>
            {{ config('company.brand.short_tagline') }}
        </span>
    </div>
</div>