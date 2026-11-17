<x-layouts.app title="Beranda"
    description="Selamat datang di SantriNexus, platform digital untuk mendukung ekosistem pendidikan pesantren yang modern, terintegrasi, dan inovatif.">
    <x-shared.soon title="SantriNexus Sedang Dipersiapkan"
        description="Kami sedang membangun SantriNexus, sebuah ekosistem digital yang dirancang untuk menghadirkan pengalaman pendidikan pesantren yang lebih modern, terintegrasi, dan inovatif. Nantikan peluncuran resminya."
        eyebrow="COMING SOON" status="Dalam Tahap Pengembangan" icon="heroicon-o-rocket-launch"
        iconFallback="fa-solid fa-rocket" iconClass="size-10" actionUrl="{{ route('login') }}" actionLabel="Masuk ke Akun"
        secondaryUrl="{{ route('register') }}" secondaryLabel="Buat Akun" />
</x-layouts.app>
