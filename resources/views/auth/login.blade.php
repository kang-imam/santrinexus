<x-layouts.auth title="Masuk"
    description="Halaman autentikasi SantriNexus sedang dalam pengembangan. Fitur masuk akun akan segera tersedia."
    maxWidth="max-w-3xl">
    <x-shared.soon title="Akses Akun Segera Hadir"
        description="Kami sedang mempersiapkan sistem autentikasi SantriNexus untuk menghadirkan pengalaman masuk akun yang aman, nyaman, dan terintegrasi. Nantikan kemudahan mengakses seluruh layanan dan fitur SantriNexus melalui satu akun."
        eyebrow="ACCOUNT AUTHENTICATION" status="Sistem Login Sedang Dikembangkan" icon="heroicon-o-lock-closed"
        iconFallback="fa-solid fa-lock" iconClass="size-10" actionUrl="{{ route('home') }}"
        actionLabel="Kembali ke Beranda" secondaryUrl="{{ route('register') }}" secondaryLabel="Buat Akun" />
</x-layouts.auth>
