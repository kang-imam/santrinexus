<x-layouts.app title="Detail Artikel"
    description="Baca artikel, wawasan, dan informasi edukatif pilihan dari SantriNexus.">
    <x-shared.soon title="Detail Artikel Segera Hadir"
        description="Kami sedang mengembangkan halaman pembaca artikel yang menghadirkan pengalaman membaca lebih nyaman, lengkap dengan informasi penulis, kategori, waktu publikasi, daftar isi, serta rekomendasi bacaan terkait untuk memperluas wawasanmu."
        eyebrow="ARTICLE READER" status="Dalam Pengembangan" icon="heroicon-o-document-text"
        iconFallback="fa-solid fa-newspaper" actionUrl="{{ route('home') }}" actionLabel="Kembali ke Beranda"
        secondaryUrl="{{ route('home') }}" secondaryLabel="Jelajahi SantriNexus" />
</x-layouts.app>
