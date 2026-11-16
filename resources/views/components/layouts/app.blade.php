<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-layouts.shared.head />
<body class="min-h-screen bg-background text-foreground antialiased">
    <div class="min-h-screen flex flex-col">
        <x-navigations.header.index />
        <div class="flex flex-1 w-full max-w-7xl mx-auto">
            <main class="flex-1 min-w-0 p-4 sm:p-6 md:p-8">
                {{ $slot }}
            </main>
        </div>
        <x-navigations.footer.index />
    </div>
</body>
</html>