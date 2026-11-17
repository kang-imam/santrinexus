<x-layouts.app title="Privasi Akun"
    description="Kelola pengaturan privasi, visibilitas, dan perlindungan data pribadi akun SantriNexus.">
    <x-shared.soon title="Pengaturan Privasi Segera Hadir"
        description="Kami sedang mengembangkan pusat pengaturan privasi yang memungkinkanmu mengelola visibilitas informasi pribadi, mengatur izin akses data, mengontrol preferensi privasi, dan memahami bagaimana informasi akun digunakan untuk memberikan pengalaman SantriNexus yang lebih aman."
        eyebrow="PRIVACY SETTINGS" status="Dalam Pengembangan" icon="heroicon-o-lock-closed"
        iconFallback="fa-solid fa-user-shield" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
