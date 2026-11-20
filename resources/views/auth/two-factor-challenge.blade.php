<x-layouts.auth title="Verifikasi Dua Faktor" description="Verifikasi identitas Anda untuk melanjutkan ke SantriNexus."
    max-width="max-w-md">
    <div class="mx-auto flex w-full flex-col gap-7 px-4 py-8 sm:px-0 sm:py-10">
        <x-auth.shared.brand size="lg" tagline="Core Digital Ecosystem" alignment="center"
            description="Satu akun untuk seluruh ekosistem digital SantriNexus." />
        <x-auth.shared.card padding="lg" variant="glass" glow>
            <div class="space-y-6" x-data="{
                method: 'authenticator',
                loading: false,
                selectMethod(method) {
                    if (this.loading) return;
                    this.method = method;
                    this.$nextTick(() => {
                        if (method === 'authenticator') {
                            this.$refs.authenticatorInput?.focus();
                        } else {
                            this.$refs.recoveryInput?.focus();
                        }
                    });
                }
            }">
                <x-auth.shared.heading eyebrow="ACCOUNT SECURITY" title="Verifikasi Identitas"
                    description="Akun Anda dilindungi autentikasi dua faktor. Masukkan kode verifikasi untuk menyelesaikan proses masuk."
                    alignment="center" size="2xl" />
                <x-auth.feedback.validation-errors title="Verifikasi gagal" variant="inline" dismissible />
                @if (session('status'))
                    <x-auth.feedback.status-message :message="session('status')" />
                @endif
                <div class="space-y-3">
                    <p class="text-center text-sm font-medium text-muted">
                        Pilih metode verifikasi
                    </p>
                    <div class="grid grid-cols-2 gap-2 rounded-xl bg-surface-raised p-1" role="group"
                        aria-label="Metode verifikasi">
                        <button type="button" @click="selectMethod('authenticator')"
                            :aria-pressed="method === 'authenticator'" :disabled="loading"
                            :class="method === 'authenticator'
                                ?
                                'bg-primary text-white shadow-sm' :
                                'text-muted hover:bg-surface-hover hover:text-foreground'"
                            class="flex min-h-11 items-center justify-center gap-2 rounded-lg px-3 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-60">
                            <x-shared.icon name="heroicon-o-device-phone-mobile" class="size-4" />
                            Authenticator
                        </button>
                        <button type="button" @click="selectMethod('recovery')" :aria-pressed="method === 'recovery'"
                            :disabled="loading"
                            :class="method === 'recovery'
                                ?
                                'bg-primary text-white shadow-sm' :
                                'text-muted hover:bg-surface-hover hover:text-foreground'"
                            class="flex min-h-11 items-center justify-center gap-2 rounded-lg px-3 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-60">
                            <x-shared.icon name="heroicon-o-key" class="size-4" />
                            Recovery Code
                        </button>
                    </div>
                </div>
                <form method="POST" action="{{ route('two-factor.challenge.store') }}" class="space-y-5"
                    x-on:submit="loading = true">
                    @csrf
                    <div x-show="method === 'authenticator'" x-cloak class="space-y-4">
                        <x-auth.forms.otp-input name="code" label="Kode Autentikasi"
                            description="Buka aplikasi authenticator Anda dan masukkan kode 6 digit yang sedang ditampilkan."
                            length="6" size="lg" autocomplete="one-time-code" auto-focus required
                            disabled-expression="method !== 'authenticator' || loading" x-ref="authenticatorInput" />
                    </div>
                    <div x-show="method === 'recovery'" x-cloak class="space-y-4">
                        <x-auth.shared.description>
                            Gunakan salah satu recovery code yang sebelumnya Anda simpan. Setiap recovery code hanya
                            dapat digunakan satu kali.
                        </x-auth.shared.description>
                        <x-auth.forms.input name="recovery_code" label="Recovery Code"
                            placeholder="Masukkan recovery code" autocomplete="off" icon="heroicon-o-key" required
                            x-bind:disabled="method !== 'recovery' || loading" x-ref="recoveryInput" />
                    </div>
                    <x-auth.forms.submit-button variant="primary" loading-text="Memverifikasi..." loading-on-submit
                        icon-right="heroicon-o-shield-check">
                        Verifikasi dan Lanjutkan
                    </x-auth.forms.submit-button>
                </form>
                <div class="rounded-xl border border-border/70 bg-surface-raised/50 p-4">
                    <div class="flex items-start gap-3">
                        <x-shared.icon name="heroicon-o-information-circle"
                            class="mt-0.5 size-5 shrink-0 text-primary" />
                        <div class="space-y-1">
                            <p class="text-sm font-semibold text-foreground">
                                Jaga keamanan kode Anda
                            </p>
                            <p class="text-xs leading-5 text-muted">
                                Jangan bagikan kode autentikasi maupun recovery code kepada siapa pun, termasuk pihak
                                yang mengaku sebagai tim SantriNexus.
                            </p>
                        </div>
                    </div>
                </div>
                <x-auth.navigation.divider />
                <form method="POST" action="{{ route('two-factor.challenge.destroy') }}" x-on:submit="loading = true">
                    @csrf
                    @method('DELETE')
                    <button type="submit" :disabled="loading"
                        class="flex w-full items-center justify-center gap-2 py-2 text-sm font-medium text-muted transition hover:text-danger focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-60">
                        <x-shared.icon name="heroicon-o-arrow-left" class="size-4" />
                        Batalkan dan Kembali ke Login
                    </button>
                </form>
            </div>
        </x-auth.shared.card>
        <p class="text-center text-xs leading-5 text-subtle">
            Dilindungi oleh sistem keamanan SantriNexus.
        </p>
    </div>
</x-layouts.auth>
