<x-layouts.auth title="Atur Ulang Kata Sandi" description="Buat password baru untuk akun SantriNexus."
    max-width="max-w-md">
    <div class="mx-auto flex w-full flex-col gap-7 px-4 py-8 sm:px-0 sm:py-10">
        <x-auth.shared.brand size="lg" tagline="Core Digital Ecosystem" alignment="center"
            description="Satu akun untuk seluruh ekosistem digital SantriNexus." />
        <x-auth.shared.card padding="lg" variant="glass" glow>
            <div class="space-y-6">
                <x-auth.shared.heading eyebrow="PASSWORD RESET" title="Buat Password Baru"
                    description="Masukkan password baru untuk memulihkan akses akun Anda." alignment="center"
                    size="2xl" />
                <x-auth.feedback.validation-errors title="Reset password gagal" variant="inline" dismissible />
                <form method="POST" action="{{ route('password.update') }}" class="space-y-5" x-data="{ loading: false }"
                    x-on:submit="loading = true">
                    @csrf
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">
                    <x-auth.forms.input name="email" label="Alamat Email" type="email" :value="request()->query('email')"
                        placeholder="nama@email.com" autocomplete="email" icon="heroicon-o-envelope" required
                        readonly />
                    <div class="space-y-4">
                        <x-auth.forms.password name="password" label="Password Baru"
                            placeholder="Masukkan password baru" autocomplete="new-password" required />
                        <x-auth.forms.password name="password_confirmation" label="Konfirmasi Password Baru"
                            placeholder="Masukkan ulang password baru" autocomplete="new-password" required />
                    </div>
                    <x-auth.forms.submit-button type="submit" variant="primary" size="lg"
                        loading-text="Memperbarui password..." loading-on-submit icon-right="heroicon-o-check">
                        Simpan Password Baru
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
    </div>
</x-layouts.auth>
