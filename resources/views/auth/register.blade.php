<x-layouts.auth title="Daftar" description="Buat akun SantriNexus untuk mulai menggunakan seluruh ekosistem digital."
    max-width="max-w-lg">
    <div class="mx-auto flex w-full flex-col gap-7 px-4 py-8 sm:px-0 sm:py-10">
        <x-auth.shared.brand size="lg" tagline="Core Digital Ecosystem" alignment="center"
            description="Satu akun untuk mengakses seluruh ekosistem digital SantriNexus." />
        <x-auth.shared.card padding="lg" variant="glass" glow>
            <div class="space-y-6">
                <x-auth.shared.heading eyebrow="Bergabung Bersama Kami" title="Buat Akun Baru"
                    description="Lengkapi informasi berikut untuk membuat akun SantriNexus Anda." alignment="center"
                    size="2xl" />
                <x-auth.feedback.validation-errors title="Pendaftaran gagal" variant="inline" dismissible />
                <form method="POST" action="{{ route('register.store') }}" class="space-y-5" x-data="{ loading: false }"
                    x-on:submit="loading = true">
                    @csrf
                    <div class="space-y-4">
                        <x-auth.forms.input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap"
                            autocomplete="name" icon="heroicon-o-user" required autofocus />
                        <x-auth.forms.input name="username" label="Username" placeholder="Contoh: santri123"
                            autocomplete="username" icon="heroicon-o-at-symbol"
                            hint="Username digunakan untuk masuk ke akun Anda." />
                        <x-auth.forms.input name="email" type="email" label="Alamat Email"
                            placeholder="nama@email.com" autocomplete="email" inputmode="email"
                            icon="heroicon-o-envelope" required />
                        <x-auth.forms.input name="phone" type="tel" label="Nomor Telepon"
                            placeholder="Contoh: 081234567890" autocomplete="tel" inputmode="tel"
                            icon="heroicon-o-phone"
                            hint="Gunakan nomor telepon aktif dengan kode negara Indonesia atau format lokal."
                            required />
                        <x-auth.forms.password name="password" label="Password" placeholder="Buat password"
                            autocomplete="new-password"
                            hint="Gunakan minimal 8 karakter yang mengandung huruf dan angka." required />
                        <x-auth.forms.password name="password_confirmation" label="Konfirmasi Password"
                            placeholder="Masukkan kembali password" autocomplete="new-password" required />
                    </div>
                    <x-auth.feedback.validation-errors field="terms" title="Persetujuan diperlukan" variant="subtle" />
                    <div class="space-y-2">
                        <x-auth.forms.checkbox name="terms" value="1" size="md" required :show-error="true">
                            Saya menyetujui
                            <a href="{{ route('legal.terms-of-service') }}" target="_blank" rel="noopener noreferrer"
                                class="font-medium text-primary underline underline-offset-4 hover:text-primary/80">
                                Ketentuan Layanan
                            </a>
                            dan
                            <a href="{{ route('legal.privacy-policy') }}" target="_blank" rel="noopener noreferrer"
                                class="font-medium text-primary underline underline-offset-4 hover:text-primary/80">
                                Kebijakan Privasi
                            </a>
                            SantriNexus.
                        </x-auth.forms.checkbox>
                    </div>
                    <x-auth.forms.submit-button type="submit" variant="primary" size="lg"
                        loading-text="Membuat akun..." loading-on-submit icon-right="heroicon-o-arrow-right">
                        Buat Akun
                    </x-auth.forms.submit-button>
                </form>
                <x-auth.navigation.divider />
                <x-auth.social.social-login :providers="['google']" variant="outline" size="lg" layout="vertical"
                    show-icons />
            </div>
        </x-auth.shared.card>
        <div class="text-center">
            <p class="text-sm text-muted">
                Sudah memiliki akun?
                <a href="{{ route('login') }}"
                    class="font-semibold text-primary transition hover:text-primary/80 focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                    Masuk sekarang
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth>
