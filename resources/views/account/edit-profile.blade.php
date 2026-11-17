<x-layouts.app title="Edit Profil" description="Perbarui informasi pribadi dan identitas akun SantriNexus.">
    <x-shared.soon title="Pengaturan Profil Segera Hadir"
        description="Kami sedang menyiapkan fitur pengeditan profil agar kamu dapat memperbarui informasi pribadi, mengelola identitas akun, dan memastikan seluruh informasi profil tetap akurat."
        eyebrow="EDIT ACCOUNT PROFILE" status="Dalam Pengembangan" icon="heroicon-o-user-circle"
        iconFallback="fa-solid fa-user-pen" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
