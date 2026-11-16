<div>
    <h3 class="mb-6 text-sm font-bold text-foreground">Navigasi</h3>
    <ul class="space-y-4">
        <li>
            <x-navigations.footer.partials.navigation-link href="{{ url('/') }}" label="Beranda" icon="heroicon-o-home" />
        </li>
        <li>
            <x-navigations.footer.partials.navigation-link href="{{ url('/about') }}" label="Tentang Kami" icon="heroicon-o-building-office-2" />
        </li>
        <li>
            <x-navigations.footer.partials.navigation-link href="{{ url('/services') }}" label="Layanan" icon="heroicon-o-squares-2x2" />
        </li>
        <li>
            <x-navigations.footer.partials.navigation-link href="{{ url('/contact') }}" label="Kontak" icon="heroicon-o-chat-bubble-left-right" />
        </li>
    </ul>
</div>