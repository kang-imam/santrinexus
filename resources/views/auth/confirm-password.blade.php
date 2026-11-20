<x-layouts.auth title="Konfirmasi Kata Sandi"
    description="Konfirmasi identitas dengan memasukkan kata sandi sebelum melanjutkan.">
    <x-auth.shared.card title="Konfirmasi Keamanan"
        description="Masukkan kata sandi akunmu untuk memastikan bahwa kamu benar-benar pemilik akun ini." width="md"
        centered>
        <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-6">
            @csrf
            <x-auth.feedback.validation-errors title="Konfirmasi Gagal" />
            <x-auth.forms.password name="password" label="Kata Sandi Saat Ini" placeholder="Masukkan kata sandi saat ini"
                autocomplete="current-password" required autofocus />
            <x-auth.forms.submit-button icon="heroicon-o-shield-check" loadingOnSubmit loadingText="Memverifikasi...">
                Konfirmasi dan Lanjutkan
            </x-auth.forms.submit-button>
            <div class="text-center">
                <a href="{{ route('home') }}"
                    class="text-sm font-medium text-muted transition hover:text-primary">Kembali ke Beranda</a>
            </div>
        </form>
    </x-auth.shared.card>
</x-layouts.auth>
