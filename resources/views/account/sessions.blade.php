<x-layouts.app title="Sesi Aktif"
    description="Pantau dan kelola perangkat serta sesi yang memiliki akses ke akun SantriNexus.">
    <x-shared.soon title="Manajemen Sesi Segera Hadir"
        description="Kami sedang mengembangkan fitur manajemen sesi yang memungkinkanmu melihat perangkat yang sedang masuk, memantau aktivitas akses akun, dan mengelola sesi aktif untuk menjaga keamanan akun SantriNexus."
        eyebrow="ACTIVE SESSIONS" status="Dalam Pengembangan" icon="heroicon-o-computer-desktop"
        iconFallback="fa-solid fa-laptop" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
