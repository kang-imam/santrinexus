<x-layouts.auth title="Login" description="Halaman login SantriNexus sedang dalam pengembangan." max-width="max-w-4xl">
    <x-pages.soon title="Login Segera Hadir"
        description="Kami sedang mempersiapkan sistem autentikasi SantriNexus yang aman, modern, dan mudah digunakan. Nantikan pengalaman login yang lebih baik untuk mengakses seluruh layanan SantriNexus."
        eyebrow="AUTHENTICATION · COMING SOON" status="Sistem Login Dalam Pengembangan" icon="heroicon-o-lock-closed"
        icon-class="size-11" action-url="{{ url('/') }}" action-label="Kembali ke Beranda"
        secondary-url="{{ route('register') }}" secondary-label="Lihat Registrasi" />
</x-layouts.auth>
