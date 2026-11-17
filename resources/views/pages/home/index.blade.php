<x-layouts.app title="Beranda"
    description="SantriNexus menghadirkan pengalaman digital yang lebih baik untuk mendukung pengelolaan dan kebutuhan pesantren.">
    <x-shared.soon title="Beranda Sedang Dipersiapkan"
        description="Kami sedang membangun pengalaman SantriNexus yang lebih modern, terintegrasi, dan mudah digunakan. Berbagai fitur baru sedang dikembangkan untuk memberikan pengalaman terbaik bagi seluruh pengguna."
        eyebrow="HOMEPAGE UNDER DEVELOPMENT" status="Beranda dalam tahap pengembangan" icon="heroicon-o-home"
        iconFallback="fa-solid fa-house" iconClass="size-10" actionUrl="{{ route('login') }}" actionLabel="Masuk ke Akun"
        secondaryUrl="{{ url('/') }}" secondaryLabel="Kunjungi SantriNexus" />
</x-layouts.app>
