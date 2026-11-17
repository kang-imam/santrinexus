<x-layouts.auth title="Masuk" description="Masuk ke akun SantriNexus untuk melanjutkan aktivitas Anda."
    max-width="max-w-md">
    <div class="space-y-6">
        <div class="text-center">
            <x-auth.shared.brand />
        </div>
        <x-auth.shared.card padding="lg">
            <div class="mb-7 text-center">
                <x-auth.shared.heading title="Selamat Datang Kembali" alignment="center" />
                <x-auth.shared.description>
                    Masuk ke akun Anda untuk mengakses SantriNexus.
                </x-auth.shared.description>
            </div>
            <x-auth.feedback.validation-errors />
            @if ($errors->has('google'))
                <x-auth.feedback.error-message :message="$errors->first('google')" />
            @endif
            @if (session('status'))
                <x-auth.feedback.success-message :message="session('status')" />
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <x-auth.forms.input name="email" label="Alamat Email" type="email" placeholder="nama@email.com"
                    autocomplete="email" icon="heroicon-o-envelope" required autofocus />
                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <label for="password" class="text-sm font-medium text-foreground">Password</label>
                        <x-auth.navigation.auth-link route="password.request" variant="subtle" size="xs">
                            Lupa password?
                        </x-auth.navigation.auth-link>
                    </div>
                    <x-auth.forms.password name="password" label="" placeholder="Masukkan password"
                        autocomplete="current-password" required />
                </div>
                <div class="flex items-center justify-between gap-3">
                    <x-auth.forms.checkbox name="remember" label="Ingat saya" :value="1" :checked="(bool) old('remember')"
                        size="sm" />
                </div>
                <x-auth.forms.submit-button full-width size="lg">
                    Masuk ke Akun
                </x-auth.forms.submit-button>
            </form>
            <x-auth.navigation.divider text="Atau masuk dengan" spacing="lg" />
            <x-auth.social.social-login :providers="['google']" layout="vertical" full-width />
            <div class="mt-7 border-t border-border pt-6 text-center">
                <p class="text-sm text-muted">
                    Belum memiliki akun?
                    <x-auth.navigation.auth-link route="register" variant="primary" size="sm">
                        Daftar sekarang
                    </x-auth.navigation.auth-link>
                </p>
            </div>
        </x-auth.shared.card>
        <p class="text-center text-xs leading-5 text-subtle">
            Dengan masuk, Anda menyetujui ketentuan layanan dan kebijakan privasi SantriNexus.
        </p>
    </div>
</x-layouts.auth>
