<x-layouts.auth title="Masuk dengan Magic Link"
    description="Akses akun SantriNexus menggunakan tautan autentikasi aman yang dikirim ke email terdaftar.">
    <x-shared.soon title="Magic Link Segera Hadir"
        description="Kami sedang mengembangkan metode autentikasi tanpa kata sandi yang memungkinkanmu masuk ke akun SantriNexus melalui tautan verifikasi sekali pakai yang dikirimkan ke alamat email terdaftar, sehingga proses masuk menjadi lebih praktis dan tetap aman."
        eyebrow="PASSWORDLESS AUTHENTICATION" status="Dalam Pengembangan" icon="heroicon-o-envelope"
        iconFallback="fa-solid fa-envelope-circle-check" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Gunakan Kata Sandi" />
</x-layouts.auth>
