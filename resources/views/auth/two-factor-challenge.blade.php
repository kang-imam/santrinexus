<x-layouts.auth title="Verifikasi Dua Faktor"
    description="Verifikasi identitas menggunakan autentikasi dua faktor untuk melindungi akun SantriNexus.">
    <x-shared.soon title="Verifikasi Dua Faktor Segera Hadir"
        description="Kami sedang mengembangkan sistem autentikasi dua faktor yang memberikan lapisan perlindungan tambahan melalui kode verifikasi atau metode autentikasi alternatif. Fitur ini dirancang untuk memastikan hanya pemilik akun yang dapat menyelesaikan proses masuk ke SantriNexus."
        eyebrow="TWO-FACTOR AUTHENTICATION" status="Dalam Pengembangan" icon="heroicon-o-device-phone-mobile"
        iconFallback="fa-solid fa-mobile-screen-button" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Kembali ke Halaman Masuk" />
</x-layouts.auth>
