<footer class="relative mt-auto overflow-hidden border-t border-border bg-background">
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 left-0 h-80 w-80 rounded-full bg-cyan-dark/10 blur-[100px]"></div>
        <div class="absolute -bottom-40 right-0 h-80 w-80 rounded-full bg-secondary/10 blur-[100px]"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 py-14 sm:grid-cols-2 lg:grid-cols-12 lg:gap-10 lg:py-16">
            <div class="lg:col-span-4">
                <x-navigations.footer.brand />
            </div>
            <div class="lg:col-span-2">
                <x-navigations.footer.navigation />
            </div>
            <div class="lg:col-span-3">
                <x-navigations.footer.contact />
            </div>
        </div>
        <div class="border-t border-border">
            <x-navigations.footer.copyright />
        </div>
    </div>
</footer>
