<x-layouts.auth title="Konfirmasi Kata Sandi"
    description="Konfirmasi identitas dengan memasukkan kata sandi sebelum melanjutkan ke halaman yang dilindungi.">
    <x-shared.soon title="Konfirmasi Keamanan Segera Hadir"
        description="Kami sedang menyiapkan mekanisme konfirmasi keamanan yang memungkinkanmu memverifikasi identitas menggunakan kata sandi sebelum mengakses halaman sensitif, mengubah pengaturan penting, atau melakukan tindakan yang memerlukan perlindungan tambahan."
        eyebrow="PASSWORD CONFIRMATION" status="Dalam Pengembangan" icon="heroicon-o-shield-check"
        iconFallback="fa-solid fa-shield-halved" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Masuk ke Akun" />
</x-layouts.auth>
