<x-layouts.app title="Riwayat Invoice"
    description="Lihat dan kelola riwayat invoice serta dokumen transaksi akun SantriNexus.">
    <x-shared.soon title="Riwayat Invoice Segera Hadir"
        description="Kami sedang menyiapkan pusat pengelolaan invoice yang memungkinkanmu melihat daftar tagihan, memeriksa rincian transaksi, memantau status pembayaran, dan mengakses dokumen invoice untuk setiap transaksi layanan SantriNexus."
        eyebrow="INVOICE HISTORY" status="Dalam Pengembangan" icon="heroicon-o-document-text"
        iconFallback="fa-solid fa-file-invoice" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
