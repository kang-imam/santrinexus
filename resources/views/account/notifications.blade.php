<x-layouts.app title="Notifikasi" description="Kelola notifikasi dan preferensi pemberitahuan akun SantriNexus.">
    <x-shared.soon title="Pengaturan Notifikasi Segera Hadir"
        description="Kami sedang menyiapkan pusat pengelolaan notifikasi yang memungkinkanmu mengatur pemberitahuan aktivitas akun, informasi penting, pembaruan fitur, serta preferensi komunikasi agar tidak melewatkan informasi yang relevan."
        eyebrow="NOTIFICATION SETTINGS" status="Dalam Pengembangan" icon="heroicon-o-bell" iconFallback="fa-solid fa-bell"
        actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda" secondaryUrl="{{ route('home') }}"
        secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
