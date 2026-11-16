@props([
    'title' => null,
    'description' => null,
    'maxWidth' => 'max-w-md',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-layouts.shared.head :title="$title" :description="$description" robots="noindex, nofollow" />

<body class="min-h-dvh overflow-x-hidden bg-background text-foreground antialiased">
    <div class="relative flex min-h-dvh flex-col">
        <a href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-surface-raised focus:px-4 focus:py-2 focus:text-foreground focus:outline-2 focus:outline-primary">
            Lewati ke konten
        </a>
        <main id="main-content" tabindex="-1"
            class="relative flex flex-1 flex-col items-center justify-center px-4 py-10 sm:px-6">
            <div class="pointer-events-none absolute inset-0 -z-0 overflow-hidden" aria-hidden="true">
                <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-primary/10 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-secondary/10 blur-3xl"></div>
            </div>
            <div class="relative z-10 w-full {{ $maxWidth }}">
                {{ $slot }}
            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>
