<x-layouts.auth title="Lupa Kata Sandi" description="Pulihkan akses akun SantriNexus melalui email terdaftar."
    max-width="max-w-md">
    <div class="mx-auto flex w-full flex-col gap-7 px-4 py-8 sm:px-0 sm:py-10">
        <x-auth.shared.brand size="lg" tagline="Core Digital Ecosystem" alignment="center"
            description="Satu akun untuk seluruh ekosistem digital SantriNexus." />
        <x-auth.shared.card padding="lg" variant="glass" glow>
            <div class="space-y-6">
                <x-auth.shared.heading eyebrow="PASSWORD RECOVERY" title="Lupa Password?"
                    description="Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password."
                    alignment="center" size="2xl" />
                <x-auth.feedback.validation-errors title="Permintaan gagal" variant="inline" dismissible />
                @if (session('status'))
                    <x-auth.feedback.status-message :message="session('status')" />
                @endif
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5" x-data="{ loading: false }"
                    x-on:submit="loading = true">
                    @csrf
                    <x-auth.forms.input name="email" label="Alamat Email" type="email" placeholder="nama@email.com"
                        autocomplete="email" icon="heroicon-o-envelope" required autofocus />
                    <x-auth.forms.submit-button variant="primary" loading-text="Mengirim tautan..." loading-on-submit
                        icon-right="heroicon-o-paper-airplane">
                        Kirim Tautan Reset
                    </x-auth.forms.submit-button>
                </form>
                <x-auth.navigation.divider />
                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-primary transition hover:text-primary/80 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                        Kembali ke halaman masuk
                    </a>
                </div>
            </div>
        </x-auth.shared.card>
        <p class="text-center text-xs leading-5 text-subtle">
            Jika email terdaftar, Anda akan menerima instruksi pemulihan password.
        </p>
    </div>
</x-layouts.auth>
