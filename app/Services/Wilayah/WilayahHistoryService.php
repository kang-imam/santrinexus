<?php

namespace App\Services\Wilayah;

use App\Models\Wilayah;
use App\Models\WilayahHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class WilayahHistoryService
{
    private const ALLOWED_ACTIONS = [
        WilayahHistory::ACTION_CREATED,
        WilayahHistory::ACTION_UPDATED,
        WilayahHistory::ACTION_DELETED,
        WilayahHistory::ACTION_RESTORED,
        WilayahHistory::ACTION_ACTIVATED,
        WilayahHistory::ACTION_DEACTIVATED,
    ];

    private const TRACKED_ATTRIBUTES = [
        'kode',
        'nama',
        'parent_code',
        'level',
        'is_active',
    ];

    public function created(Wilayah $wilayah): WilayahHistory
    {
        return $this->record(
            $wilayah,
            WilayahHistory::ACTION_CREATED,
            null,
            $this->snapshot($wilayah)
        );
    }

    public function updated(Wilayah $wilayah, array $oldValues, array $newValues): ?WilayahHistory
    {
        $changes = $this->getChanges($oldValues, $newValues);
        if (empty($changes['new'])) {
            return null;
        }

        return $this->record(
            $wilayah,
            WilayahHistory::ACTION_UPDATED,
            $changes['old'],
            $changes['new']
        );
    }

    public function deleted(Wilayah $wilayah): WilayahHistory
    {
        return $this->record(
            $wilayah,
            WilayahHistory::ACTION_DELETED,
            $this->snapshot($wilayah),
            null
        );
    }

    public function restored(Wilayah $wilayah): WilayahHistory
    {
        return $this->record(
            $wilayah,
            WilayahHistory::ACTION_RESTORED,
            null,
            $this->snapshot($wilayah)
        );
    }

    public function activated(Wilayah $wilayah): WilayahHistory
    {
        return $this->changeStatus($wilayah, false, true);
    }

    public function deactivated(Wilayah $wilayah): WilayahHistory
    {
        return $this->changeStatus($wilayah, true, false);
    }

    public function changeStatus(Wilayah $wilayah, bool $oldStatus, bool $newStatus): WilayahHistory
    {
        if ($oldStatus === $newStatus) {
            throw new InvalidArgumentException('Status wilayah tidak mengalami perubahan.');
        }

        return $this->record(
            $wilayah,
            $newStatus
                ? WilayahHistory::ACTION_ACTIVATED
                : WilayahHistory::ACTION_DEACTIVATED,
            ['is_active' => $oldStatus],
            ['is_active' => $newStatus]
        );
    }

    public function record(
        Wilayah $wilayah,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null
    ): WilayahHistory {
        if (! in_array($action, self::ALLOWED_ACTIONS, true)) {
            throw new InvalidArgumentException('Jenis aktivitas riwayat wilayah tidak valid.');
        }

        return WilayahHistory::create([
            'wilayah_id' => $wilayah->id,
            'kode' => $wilayah->kode,
            'action' => $action,
            'old_values' => $this->filterAttributes($oldValues),
            'new_values' => $this->filterAttributes($newValues),
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);
    }

    public function snapshot(Wilayah $wilayah): array
    {
        return $this->filterAttributes(
            $wilayah->only(self::TRACKED_ATTRIBUTES)
        );
    }

    public function history(string $kode, int $perPage = 20): LengthAwarePaginator
    {
        $wilayah = Wilayah::withTrashed()->where('kode', $kode)->first();
        $query = WilayahHistory::query()->with('user:id,name');
        if ($wilayah) {
            $query->where('wilayah_id', $wilayah->id);
        } else {
            $query->where('kode', $kode);
        }

        return $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(max(1, min($perPage, 100)));
    }

    public function historyForWilayah(Wilayah $wilayah, int $perPage = 20): LengthAwarePaginator
    {
        return WilayahHistory::query()
            ->where('wilayah_id', $wilayah->id)
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(max(1, min($perPage, 100)));
    }

    private function getChanges(array $oldValues, array $newValues): array
    {
        $old = [];
        $new = [];
        foreach ($this->filterAttributes($newValues) as $key => $value) {
            $previous = $oldValues[$key] ?? null;
            if ($previous !== $value) {
                $old[$key] = $previous;
                $new[$key] = $value;
            }
        }

        return [
            'old' => $old,
            'new' => $new,
        ];
    }

    private function filterAttributes(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        return array_intersect_key($values, array_flip(self::TRACKED_ATTRIBUTES));
    }
}
