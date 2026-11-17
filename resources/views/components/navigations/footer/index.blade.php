@php
    $companyName = config('company.brand.name', config('company.name', config('app.name', 'SantriNexus')));
    $footerLabel = 'Footer ' . $companyName;
@endphp
<footer aria-label="{{ $footerLabel }}"
    class="relative isolate mt-auto overflow-hidden border-t border-white/[0.08] bg-gradient-to-b from-surface via-background to-background">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-40 -top-48 h-[28rem] w-[28rem] rounded-full bg-cyan-dark/[0.12] blur-[120px]"></div>
        <div class="absolute -right-40 top-20 h-[30rem] w-[30rem] rounded-full bg-secondary/[0.09] blur-[130px]"></div>
        <div
            class="absolute bottom-0 left-1/2 h-64 w-[36rem] -translate-x-1/2 rounded-full bg-primary/[0.04] blur-[110px]">
        </div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
    </div>
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative py-12 sm:py-16 lg:py-20">
            <x-navigations.footer.navigation />
        </div>
        <div class="relative border-t border-white/[0.07]">
            <div class="py-10 sm:py-14 lg:py-16">
                <x-navigations.footer.about />
            </div>
        </div>
        <div class="relative border-t border-white/[0.07]">
            <x-navigations.footer.copyright />
        </div>
    </div>
</footer>
