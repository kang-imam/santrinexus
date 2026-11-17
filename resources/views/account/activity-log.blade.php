<x-layouts.app title="Riwayat Aktivitas"
    description="Pantau riwayat aktivitas dan perubahan yang terjadi pada akun SantriNexus.">
    <x-shared.soon title="Riwayat Aktivitas Segera Hadir"
        description="Kami sedang mengembangkan pusat riwayat aktivitas yang memungkinkanmu meninjau aktivitas akun, memantau perubahan informasi, melihat riwayat autentikasi, dan memahami berbagai aktivitas yang terjadi pada akun SantriNexus."
        eyebrow="ACCOUNT ACTIVITY LOG" status="Dalam Pengembangan" icon="heroicon-o-clock"
        iconFallback="fa-solid fa-clock-rotate-left" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
