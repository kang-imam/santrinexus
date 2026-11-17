<x-layouts.auth title="Lupa Kata Sandi" description="Pulihkan akses akun SantriNexus dengan mengatur ulang kata sandi.">
    <x-shared.soon title="Pemulihan Akun Segera Hadir"
        description="Kami sedang menyiapkan sistem pemulihan akun yang memungkinkanmu meminta tautan pengaturan ulang kata sandi melalui email terdaftar, melakukan verifikasi identitas, dan mendapatkan kembali akses akun SantriNexus dengan aman."
        eyebrow="PASSWORD RECOVERY" status="Dalam Pengembangan" icon="heroicon-o-key"
        iconFallback="fa-solid fa-unlock-keyhole" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Kembali ke Halaman Masuk" />
</x-layouts.auth>
