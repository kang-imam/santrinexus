<x-layouts.auth title="Registrasi" description="Halaman registrasi SantriNexus sedang dalam pengembangan."
    max-width="max-w-4xl">
    <x-pages.soon title="Registrasi Segera Hadir"
        description="Kami sedang membangun sistem pendaftaran SantriNexus yang praktis dan terintegrasi. Kamu akan segera dapat membuat akun dan menikmati berbagai layanan digital yang tersedia."
        eyebrow="REGISTRATION · COMING SOON" status="Sistem Registrasi Dalam Pengembangan" icon="heroicon-o-user-plus"
        icon-class="size-11" action-url="{{ url('/') }}" action-label="Kembali ke Beranda"
        secondary-url="{{ route('login') }}" secondary-label="Lihat Login" />
</x-layouts.auth>
