@props([
    'title' => null,
    'description' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-layouts.shared.head :title="$title" :description="$description" />

<body
    class="flex min-h-screen flex-col bg-background font-sans text-foreground antialiased selection:bg-primary selection:text-background">
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-100 focus:rounded-xl focus:bg-primary focus:px-5 focus:py-3 focus:font-semibold focus:text-background focus:shadow-lg focus:outline-none">
        Lewati ke konten utama
    </a>
    <x-navigations.header.index />
    <div class="mx-auto flex w-full max-w-7xl flex-1 flex-col">
        <main id="main-content" tabindex="-1" class="min-w-0 flex-1 px-0 py-0 outline-none">
            {{ $slot }}
        </main>
    </div>
    <x-navigations.footer.index />
</body>
@stack('scripts')

</html>
