<x-layouts.auth title="Konfirmasi Magic Link"
    description="Konfirmasi tautan autentikasi untuk masuk ke akun SantriNexus.">
    <div class="mx-auto w-full max-w-md space-y-6">
        <x-auth.shared.heading eyebrow="SECURE AUTHENTICATION" title="Konfirmasi Login"
            description="Tautan login telah dibuka. Tekan tombol berikut untuk melanjutkan ke akun SantriNexus." />
        <x-auth.shared.card padding="lg" width="full">
            <x-auth.feedback.validation-errors field="token" />
            <div class="mb-6 rounded-xl border border-primary/20 bg-primary/5 p-4">
                <div class="flex items-start gap-3">
                    <x-shared.icon name="heroicon-o-shield-check" class="size-6 shrink-0 text-primary"
                        aria-hidden="true" />
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-foreground">Tautan autentikasi aman</p>
                        <p class="text-xs leading-5 text-muted">Tautan ini hanya dapat digunakan satu kali dan memiliki
                            batas waktu penggunaan.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('magic-link.verify') }}" class="space-y-5" x-data="{ loading: false }"
                x-on:submit="loading = true">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <x-auth.forms.submit-button icon="heroicon-o-lock-open" loading-on-submit
                    loading-text="Memverifikasi tautan...">
                    Login Sekarang
                </x-auth.forms.submit-button>
            </form>
        </x-auth.shared.card>
        <div class="flex justify-center">
            <x-auth.navigation.back-link href="{{ route('magic-link') }}">
                Kembali ke Magic Link
            </x-auth.navigation.back-link>
        </div>
    </div>
</x-layouts.auth>
