<x-layouts.auth title="Masuk" description="Masuk ke akun SantriNexus untuk melanjutkan aktivitas Anda."
    max-width="max-w-md">
    <div class="mx-auto flex w-full flex-col gap-7 px-4 py-8 sm:px-0 sm:py-10">
        <x-auth.shared.brand size="lg" tagline="Core Digital Ecosystem" alignment="center"
            description="Satu akun untuk seluruh ekosistem digital SantriNexus." />
        <x-auth.shared.card padding="lg" variant="glass" glow>
            <div class="space-y-6">
                <x-auth.shared.heading eyebrow="Selamat Datang Kembali" title="Masuk ke Akun"
                    description="Masukkan identitas akun dan password untuk melanjutkan." alignment="center"
                    size="2xl" />
                <x-auth.feedback.validation-errors title="Login gagal" variant="inline" dismissible />
                @if (session('status'))
                    <x-auth.feedback.status-message :message="session('status')" />
                @endif
                <form method="POST" action="{{ route('login.store') }}" class="space-y-5" x-data="{ loading: false }"
                    x-on:submit="loading = true">
                    @csrf
                    <div class="space-y-4">
                        <x-auth.forms.input name="identifier" label="Email, Username, atau Nomor Telepon"
                            placeholder="Masukkan email, username, atau nomor telepon" autocomplete="username"
                            icon="heroicon-o-user" required autofocus />
                        <x-auth.forms.password name="password" label="Password" placeholder="Masukkan password"
                            autocomplete="current-password" required />
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <x-auth.forms.checkbox name="remember" label="Ingat saya" value="1" size="md" />
                        <a href="{{ route('password.request') }}"
                            class="text-sm font-medium text-primary transition hover:text-primary/80 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                            Lupa password?
                        </a>
                    </div>
                    <x-auth.forms.submit-button variant="primary" loading-text="Sedang masuk..." loading-on-submit
                        icon-right="heroicon-o-arrow-right">
                        Masuk
                    </x-auth.forms.submit-button>
                </form>
                <div class="flex flex-col items-center gap-2 text-center">
                    <x-auth.navigation.auth-link :route="'magic-link'" icon="heroicon-o-envelope" variant="primary"
                        size="md" underline>
                        Masuk tanpa kata sandi
                    </x-auth.navigation.auth-link>
                    <p class="text-xs leading-5 text-muted">
                        Kami akan mengirimkan tautan login ke email terdaftar Anda.
                    </p>
                </div>
                <x-auth.navigation.divider />
                <x-auth.social.social-login :providers="['google']" variant="outline" size="lg" layout="vertical"
                    show-icons />
            </div>
        </x-auth.shared.card>
        <div class="text-center">
            <p class="text-sm text-muted">
                Belum memiliki akun?
                <a href="{{ route('register') }}"
                    class="font-semibold text-primary transition hover:text-primary/80 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                    Daftar sekarang
                </a>
            </p>
        </div>
        <p class="text-center text-xs leading-5 text-subtle">
            Dengan masuk, Anda menyetujui
            <a href="{{ route('legal.terms-of-service') }}"
                class="text-muted underline decoration-border underline-offset-4 transition hover:text-primary">Ketentuan
                Layanan</a>
            dan
            <a href="{{ route('legal.privacy-policy') }}"
                class="text-muted underline decoration-border underline-offset-4 transition hover:text-primary">Kebijakan
                Privasi</a>
            SantriNexus.
        </p>
    </div>
</x-layouts.auth>
