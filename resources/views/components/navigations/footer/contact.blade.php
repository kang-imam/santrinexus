<div>
    <h3 class="mb-6 text-sm font-bold text-foreground">Hubungi Kami</h3>
    <div class="space-y-5">
        <a href="mailto:{{ config('company.contact.emails.general') }}" class="group flex items-start gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-border bg-surface-raised text-primary transition-colors group-hover:border-primary/30 group-hover:bg-primary/10">
                <x-heroicon-o-envelope class="h-4 w-4" />
            </span>
            <span class="min-w-0">
                <span class="block text-xs text-subtle">Email</span>
                <span class="mt-1 block break-all text-sm text-muted transition-colors group-hover:text-primary">{{ config('company.contact.emails.general') }}</span>
            </span>
        </a>
        <a href="{{ config('company.contact.whatsapp.url') }}" target="_blank" rel="noopener noreferrer" class="group flex items-start gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-border bg-surface-raised text-primary transition-colors group-hover:border-primary/30 group-hover:bg-primary/10">
                <x-heroicon-o-phone class="h-4 w-4" />
            </span>
            <span>
                <span class="block text-xs text-subtle">WhatsApp</span>
                <span class="mt-1 block text-sm text-muted transition-colors group-hover:text-primary">{{ config('company.contact.phone') }}</span>
            </span>
        </a>
        <a href="{{ config('company.contact.maps_url') }}" target="_blank" rel="noopener noreferrer" class="group flex items-start gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-border bg-surface-raised text-primary transition-colors group-hover:border-primary/30 group-hover:bg-primary/10">
                <x-heroicon-o-map-pin class="h-4 w-4" />
            </span>
            <span>
                <span class="block text-xs text-subtle">Alamat</span>
                <span class="mt-1 block text-sm leading-6 text-muted transition-colors group-hover:text-primary">
                    {{ config('company.contact.address.province') }} - {{ config('company.contact.address.country') }}
                </span>
            </span>
        </a>
    </div>
    <div class="mt-7">
        <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-subtle">
            Connect with us
        </p>
        <x-navigations.footer.partials.social-links />
    </div>
</div>