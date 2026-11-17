<x-layouts.auth title="Verifikasi Email"
    description="Verifikasi alamat email untuk mengaktifkan dan melindungi akun SantriNexus.">
    <x-shared.soon title="Verifikasi Email Segera Hadir"
        description="Kami sedang menyiapkan sistem verifikasi email yang memungkinkanmu mengonfirmasi kepemilikan alamat email melalui tautan verifikasi resmi. Proses ini membantu memastikan keaslian identitas, meningkatkan keamanan akun, dan membuka akses ke berbagai layanan SantriNexus."
        eyebrow="EMAIL VERIFICATION" status="Dalam Pengembangan" icon="heroicon-o-envelope-open"
        iconFallback="fa-solid fa-envelope-circle-check" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Kembali ke Halaman Masuk" />
</x-layouts.auth>
