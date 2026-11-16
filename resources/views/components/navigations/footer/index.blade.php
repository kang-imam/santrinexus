<footer class="relative isolate mt-auto overflow-hidden border-t border-border bg-background">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-40 -top-40 h-80 w-80 rounded-full bg-cyan-dark/10 blur-[100px]"></div>
        <div class="absolute -bottom-40 -right-40 h-80 w-80 rounded-full bg-secondary/10 blur-[100px]"></div>
    </div>
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="py-12 sm:py-14 lg:py-16">
            <x-navigations.footer.navigation />
        </div>
        <div class="border-t border-border">
            <div class="py-12 sm:py-14 lg:py-16">
                <x-navigations.footer.about />
            </div>
        </div>
        <div class="border-t border-border">
            <x-navigations.footer.copyright />
        </div>
    </div>
</footer>
