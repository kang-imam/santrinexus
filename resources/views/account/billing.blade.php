<x-layouts.app title="Tagihan dan Pembayaran"
    description="Kelola informasi tagihan, metode pembayaran, dan riwayat transaksi akun SantriNexus.">
    <x-shared.soon title="Manajemen Tagihan Segera Hadir"
        description="Kami sedang mengembangkan pusat manajemen tagihan yang memungkinkanmu melihat rincian pembayaran, memantau status transaksi, mengelola informasi penagihan, serta meninjau riwayat pembayaran layanan SantriNexus secara transparan."
        eyebrow="BILLING & PAYMENTS" status="Dalam Pengembangan" icon="heroicon-o-banknotes"
        iconFallback="fa-solid fa-money-bill-transfer" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
