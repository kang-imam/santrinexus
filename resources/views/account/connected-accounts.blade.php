<x-layouts.app title="Akun Terhubung"
    description="Kelola akun eksternal dan layanan pihak ketiga yang terhubung dengan akun SantriNexus.">
    <x-shared.soon title="Akun Terhubung Segera Hadir"
        description="Kami sedang menyiapkan pusat integrasi akun yang memungkinkanmu mengelola koneksi dengan layanan eksternal, memantau akses pihak ketiga, dan mengatur izin koneksi untuk menjaga keamanan serta kenyamanan akun SantriNexus."
        eyebrow="CONNECTED ACCOUNTS" status="Dalam Pengembangan" icon="heroicon-o-link" iconFallback="fa-solid fa-link"
        actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda" secondaryUrl="{{ route('home') }}"
        secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
