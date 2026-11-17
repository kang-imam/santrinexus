<x-layouts.auth title="Daftar Akun"
    description="Halaman pendaftaran akun SantriNexus sedang dalam pengembangan. Registrasi pengguna akan segera tersedia."
    maxWidth="max-w-3xl">
    <x-shared.soon title="Bergabung Bersama SantriNexus"
        description="Kami sedang mempersiapkan sistem pendaftaran akun untuk membuka akses ke ekosistem digital SantriNexus. Melalui satu akun terintegrasi, nantinya kamu dapat menikmati berbagai layanan dan solusi digital yang dirancang untuk mendukung kebutuhan pendidikan pesantren."
        eyebrow="JOIN OUR ECOSYSTEM" status="Sistem Registrasi Sedang Dikembangkan" icon="heroicon-o-user-plus"
        iconFallback="fa-solid fa-user-plus" iconClass="size-10" actionUrl="{{ route('home') }}"
        actionLabel="Kembali ke Beranda" secondaryUrl="{{ route('login') }}" secondaryLabel="Sudah Punya Akun?" />
</x-layouts.auth>
