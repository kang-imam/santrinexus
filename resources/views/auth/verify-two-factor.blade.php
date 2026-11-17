<x-layouts.auth title="Verifikasi Autentikasi Dua Faktor"
    description="Selesaikan verifikasi autentikasi dua faktor untuk mengamankan akses akun SantriNexus.">
    <x-shared.soon title="Verifikasi Keamanan Segera Hadir"
        description="Kami sedang menyiapkan halaman verifikasi autentikasi dua faktor yang memungkinkanmu mengonfirmasi identitas menggunakan kode keamanan atau metode verifikasi alternatif sebelum mendapatkan akses penuh ke akun SantriNexus."
        eyebrow="TWO-FACTOR VERIFICATION" status="Dalam Pengembangan" icon="heroicon-o-shield-check"
        iconFallback="fa-solid fa-user-shield" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Kembali ke Halaman Masuk" />
</x-layouts.auth>
