<x-layouts.auth title="Terima Undangan" description="Terima undangan dan bergabung dengan ekosistem SantriNexus.">
    <x-shared.soon title="Penerimaan Undangan Segera Hadir"
        description="Kami sedang menyiapkan sistem penerimaan undangan yang memungkinkanmu bergabung dengan organisasi atau komunitas di SantriNexus melalui undangan resmi, melakukan verifikasi identitas, dan menyelesaikan proses aktivasi akun dengan aman."
        eyebrow="INVITATION ACCEPTANCE" status="Dalam Pengembangan" icon="heroicon-o-envelope-open"
        iconFallback="fa-solid fa-envelope-open-text" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('login') }}" secondaryLabel="Masuk ke Akun" />
</x-layouts.auth>
