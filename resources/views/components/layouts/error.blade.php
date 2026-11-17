@props([
    'title' => 'Terjadi Kesalahan',
    'description' => null,
    'maxWidth' => 'max-w-3xl',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-layouts.shared.head :title="$title" :description="$description" robots="noindex, nofollow" />

<body
    class="min-h-dvh overflow-x-hidden bg-background font-sans text-foreground antialiased selection:bg-primary selection:text-background">
    <div class="relative isolate flex min-h-dvh flex-col overflow-hidden">
        <a href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-xl focus:bg-primary focus:px-5 focus:py-3 focus:font-semibold focus:text-background focus:shadow-lg focus:outline-none">
            Lewati ke konten utama
        </a>
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(34,211,238,0.08),transparent_55%)]">
            </div>
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,rgba(56,189,248,0.07),transparent_45%)]">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,rgba(148,163,184,0.035)_1px,transparent_1px),linear-gradient(to_bottom,rgba(148,163,184,0.035)_1px,transparent_1px)] bg-[size:48px_48px] [mask-image:radial-gradient(ellipse_at_center,black_20%,transparent_85%)]">
            </div>
            <div class="absolute -left-48 -top-48 size-[32rem] rounded-full bg-primary/[0.08] blur-[120px]"></div>
            <div class="absolute -bottom-48 -right-48 size-[32rem] rounded-full bg-secondary/[0.08] blur-[120px]"></div>
            <div
                class="absolute left-1/2 top-1/2 size-[36rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/[0.025] blur-[140px]">
            </div>
        </div>
        <main id="main-content" tabindex="-1"
            class="relative flex flex-1 flex-col items-center justify-center px-4 py-12 outline-none sm:px-6">
            <div class="relative z-10 w-full {{ $maxWidth }}">
                <div class="animate-[error-enter_0.6s_cubic-bezier(0.16,1,0.3,1)_both]">
                    {{ $slot }}
                </div>
            </div>
        </main>
        <div class="pointer-events-none absolute bottom-0 left-1/2 h-px w-1/2 -translate-x-1/2 bg-gradient-to-r from-transparent via-primary/30 to-transparent"
            aria-hidden="true"></div>
    </div>
    <style>
        @keyframes error-enter {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-\[error-enter_0\.6s_cubic-bezier\(0\.16\,1\,0\.3\,1\)_both\] {
                animation: none;
            }
        }
    </style>
    <x-shared.install-prompt />
    @stack('scripts')
</body>

</html>
