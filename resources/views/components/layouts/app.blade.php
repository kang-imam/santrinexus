@props([
    'title' => null,
    'description' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-layouts.shared.head :title="$title" :description="$description" />

<body class="min-h-screen bg-background text-foreground antialiased">
    <div class="flex min-h-screen flex-col">
        <a href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-surface-raised focus:px-4 focus:py-2 focus:text-foreground focus:outline-2 focus:outline-primary">
            Lewati ke konten
        </a>
        <x-navigations.header.index />
        <div class="mx-auto flex w-full max-w-7xl flex-1">
            <main id="main-content" tabindex="-1" class="min-w-0 flex-1 p-4 sm:p-6 md:p-8">
                {{ $slot }}
            </main>
        </div>
        <x-navigations.footer.index />
    </div>
    @stack('scripts')
</body>

</html>
