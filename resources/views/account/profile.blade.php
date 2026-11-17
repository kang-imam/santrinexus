<x-layouts.app title="Profil Saya" description="Kelola informasi profil dan identitas akun SantriNexus.">
    <x-shared.soon title="Profil Sedang Disiapkan"
        description="Kami sedang mengembangkan halaman profil yang memungkinkanmu mengelola informasi pribadi, memperbarui identitas akun, dan menyesuaikan preferensi profil dengan mudah dan aman."
        eyebrow="ACCOUNT PROFILE" status="Dalam Pengembangan" icon="heroicon-o-user-circle"
        iconFallback="fa-solid fa-circle-user" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
