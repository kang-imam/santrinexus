<x-layouts.auth title="Verifikasi Email"
    description="Verifikasi alamat email untuk mengaktifkan dan melindungi akun SantriNexus." maxWidth="max-w-md">
    <x-auth.shared.card width="full" centered>
        <div class="flex flex-col items-center gap-6">
            <div class="flex size-16 items-center justify-center rounded-2xl border border-primary/20 bg-primary/10">
                <x-shared.icon name="heroicon-o-envelope-open" class="size-8 text-primary" aria-hidden="true" />
            </div>
            <x-auth.shared.brand size="lg" show-name show-tagline alignment="center" />
            <x-auth.shared.heading eyebrow="EMAIL VERIFICATION" title="Verifikasi Email"
                description="Satu langkah lagi untuk mengaktifkan akun SantriNexus." />
            <x-auth.shared.description>
                Kami telah mengirimkan tautan verifikasi ke alamat email yang terdaftar pada akunmu. Silakan periksa
                kotak masuk dan klik tautan tersebut untuk mengonfirmasi kepemilikan alamat email.
            </x-auth.shared.description>
            <div class="w-full space-y-4">
                @if (session('success'))
                    <x-auth.feedback.success-message :message="session('success')" />
                @endif
                @if (session('warning'))
                    <x-auth.feedback.status-message :message="session('warning')" title="Perhatian" />
                @endif
                @if (session('status'))
                    <x-auth.feedback.status-message :message="session('status')" />
                @endif
                @if ($errors->any())
                    <x-auth.feedback.validation-errors />
                @endif
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-auth.forms.submit-button type="submit" icon="heroicon-o-paper-airplane">
                        Kirim Ulang Email Verifikasi
                    </x-auth.forms.submit-button>
                </form>
                <p class="text-center text-xs leading-5 text-muted">
                    Tidak menerima email? Periksa folder spam atau coba kirim ulang tautan verifikasi.
                </p>
                <div class="border-t border-border/70 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl px-4 py-3 text-sm font-medium text-muted transition hover:bg-surface-hover hover:text-foreground">
                            Keluar dari akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-auth.shared.card>
</x-layouts.auth>
