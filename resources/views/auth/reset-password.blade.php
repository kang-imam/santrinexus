<x-layouts.auth title="Atur Ulang Kata Sandi"
    description="Buat kata sandi baru untuk memulihkan akses akun SantriNexus dengan aman.">
    <x-shared.soon title="Pengaturan Ulang Kata Sandi Segera Hadir"
        description="Kami sedang menyiapkan sistem pengaturan ulang kata sandi yang memungkinkanmu memverifikasi permintaan pemulihan, membuat kredensial baru, dan mendapatkan kembali akses akun SantriNexus melalui proses yang aman dan terlindungi."
        eyebrow="PASSWORD RESET" status="Dalam Pengembangan" icon="heroicon-o-lock-closed" iconFallback="fa-solid fa-lock"
        actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda" secondaryUrl="{{ route('login') }}"
        secondaryLabel="Kembali ke Halaman Masuk" />
</x-layouts.auth>
