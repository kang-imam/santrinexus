<x-layouts.app title="Langganan"
    description="Kelola informasi paket, status langganan, dan riwayat layanan akun SantriNexus.">
    <x-shared.soon title="Manajemen Langganan Segera Hadir"
        description="Kami sedang mengembangkan pusat manajemen langganan yang memungkinkanmu melihat paket layanan, memantau status langganan, mengelola masa aktif, serta mengakses informasi dan riwayat layanan SantriNexus."
        eyebrow="SUBSCRIPTION MANAGEMENT" status="Dalam Pengembangan" icon="heroicon-o-credit-card"
        iconFallback="fa-solid fa-id-card" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
