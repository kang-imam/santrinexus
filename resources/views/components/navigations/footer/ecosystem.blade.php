<div>
    <h3 class="mb-6 text-sm font-bold text-foreground">Ekosistem Teknologi</h3>
    <ul class="space-y-3">
        @foreach (array_slice(config('company.profile.services', []), 0, 6) as $service)
            <li class="flex items-start gap-2.5 text-sm text-muted transition-colors hover:text-primary">
                <x-heroicon-o-chevron-right class="mt-0.5 h-4 w-4 shrink-0 text-primary" />
                <span>{{ $service }}</span>
            </li>
        @endforeach
    </ul>
</div>