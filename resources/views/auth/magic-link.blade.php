<x-layouts.auth title="Masuk dengan Magic Link"
    description="Akses akun SantriNexus menggunakan tautan autentikasi aman yang dikirim ke email terdaftar.">
    <div class="mx-auto w-full max-w-md space-y-6">
        <x-auth.shared.heading eyebrow="PASSWORDLESS AUTHENTICATION" title="Masuk tanpa kata sandi"
            description="Masukkan alamat email terdaftar. Kami akan mengirimkan tautan login khusus ke email kamu." />
        <x-auth.shared.card padding="lg" width="full">
            <x-auth.feedback.validation-errors />
            @if (session('status'))
                <x-auth.feedback.status-message :message="session('status')" title="Permintaan Diproses" />
            @endif
            <form method="POST" action="{{ route('magic-link.send') }}" class="space-y-5" x-data="{ loading: false }"
                x-on:submit="loading = true">
                @csrf
                <x-auth.forms.input name="email" label="Alamat Email" type="email" placeholder="nama@email.com"
                    autocomplete="email" icon="heroicon-o-envelope" required autofocus :value="old('email')"
                    hint="Gunakan alamat email yang terdaftar di akun SantriNexus." />
                <x-auth.forms.submit-button icon="heroicon-o-paper-airplane" loading-on-submit
                    loading-text="Mengirim tautan...">
                    Kirim Tautan Login
                </x-auth.forms.submit-button>
            </form>
            <div class="mt-6 border-t border-border/70 pt-5">
                <x-auth.shared.description size="xs">
                    Tautan login hanya dapat digunakan satu kali dan akan kedaluwarsa setelah batas waktu tertentu.
                </x-auth.shared.description>
            </div>
        </x-auth.shared.card>
        <div class="flex justify-center">
            <x-auth.navigation.back-link href="{{ route('login') }}">
                Kembali ke Login
            </x-auth.navigation.back-link>
        </div>
    </div>
</x-layouts.auth>
