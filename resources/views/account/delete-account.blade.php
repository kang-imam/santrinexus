<x-layouts.app title="Hapus Akun" description="Informasi dan pengelolaan permintaan penghapusan akun SantriNexus.">
    <x-shared.soon title="Pengelolaan Penghapusan Akun Segera Hadir"
        description="Kami sedang menyiapkan pusat pengelolaan akun yang memungkinkanmu mengajukan permintaan penghapusan akun, memahami konsekuensi terhadap data dan layanan, serta memantau status permintaan dengan proses yang transparan dan aman."
        eyebrow="ACCOUNT DELETION" status="Dalam Pengembangan" icon="heroicon-o-user-minus"
        iconFallback="fa-solid fa-user-xmark" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
