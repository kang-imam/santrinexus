<?php

namespace App\Services\Wilayah;

use App\Models\Wilayah;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WilayahService
{
    public function __construct(
        private WilayahValidator $validator,
        private WilayahHistoryService $historyService
    ) {}

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Wilayah::query()->with('parent');
        if (array_key_exists('level', $filters) && $filters['level'] !== null) {
            $query->where('level', $filters['level']);
        }
        if (array_key_exists('parent_code', $filters)) {
            $query->where('parent_code', $filters['parent_code']);
        }
        if (isset($filters['search']) && trim($filters['search']) !== '') {
            $query->search(trim($filters['search']));
        }
        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('kode')->paginate(max(1, min($perPage, 100)));
    }

    public function findByCode(string $kode): ?Wilayah
    {
        return Wilayah::with(['parent', 'children'])
            ->where('kode', $kode)
            ->first();
    }

    public function getChildren(string $parentCode): Collection
    {
        return Wilayah::active()
            ->where('parent_code', $parentCode)
            ->orderBy('kode')
            ->get();
    }

    public function getProvinces(): Collection
    {
        return Wilayah::active()
            ->province()
            ->orderBy('nama')
            ->get();
    }

    public function getRegencies(string $provinceCode): Collection
    {
        return Wilayah::active()
            ->regency()
            ->where('parent_code', $provinceCode)
            ->orderBy('nama')
            ->get();
    }

    public function getDistricts(string $regencyCode): Collection
    {
        return Wilayah::active()
            ->district()
            ->where('parent_code', $regencyCode)
            ->orderBy('nama')
            ->get();
    }

    public function getVillages(string $districtCode): Collection
    {
        return Wilayah::active()
            ->village()
            ->where('parent_code', $districtCode)
            ->orderBy('nama')
            ->get();
    }

    public function create(array $data): Wilayah
    {
        return DB::transaction(function () use ($data) {
            $data = $this->validator->validateForCreate($data);
            $wilayah = Wilayah::create($data);
            $this->historyService->created($wilayah);

            return $wilayah->refresh();
        });
    }

    public function update(Wilayah $wilayah, array $data): Wilayah
    {
        return DB::transaction(function () use ($wilayah, $data) {
            $wilayah = Wilayah::query()
                ->lockForUpdate()
                ->findOrFail($wilayah->id);
            $oldValues = $this->historyService->snapshot($wilayah);
            $data = $this->validator->validateForUpdate($wilayah, $data);
            $wilayah->update($data);
            $wilayah->refresh();
            $newValues = $this->historyService->snapshot($wilayah);
            $this->historyService->updated($wilayah, $oldValues, $newValues);

            return $wilayah;
        });
    }

    public function delete(Wilayah $wilayah): bool
    {
        return DB::transaction(function () use ($wilayah) {
            $wilayah = Wilayah::query()
                ->lockForUpdate()
                ->findOrFail($wilayah->id);
            $hasChildren = Wilayah::withTrashed()
                ->where('parent_code', $wilayah->kode)
                ->exists();
            if ($hasChildren) {
                throw ValidationException::withMessages([
                    'wilayah' => 'Wilayah tidak dapat dihapus karena masih memiliki wilayah turunan.',
                ]);
            }
            $this->historyService->deleted($wilayah);

            return (bool) $wilayah->delete();
        });
    }

    public function restore(string $kode): bool
    {
        return DB::transaction(function () use ($kode) {
            $wilayah = Wilayah::withTrashed()
                ->where('kode', $kode)
                ->lockForUpdate()
                ->firstOrFail();
            if (! $wilayah->trashed()) {
                return false;
            }
            $wilayah->restore();
            $wilayah->refresh();
            $this->historyService->restored($wilayah);

            return true;
        });
    }

    public function setActive(Wilayah $wilayah, bool $isActive): Wilayah
    {
        return DB::transaction(function () use ($wilayah, $isActive) {
            $wilayah = Wilayah::query()
                ->lockForUpdate()
                ->findOrFail($wilayah->id);
            $oldStatus = (bool) $wilayah->is_active;
            if ($oldStatus === $isActive) {
                return $wilayah;
            }
            $wilayah->update(['is_active' => $isActive]);
            $wilayah->refresh();
            $this->historyService->changeStatus(
                $wilayah,
                $oldStatus,
                $isActive
            );

            return $wilayah;
        });
    }

    public function history(string $kode, int $perPage = 20): LengthAwarePaginator
    {
        return $this->historyService->history($kode, $perPage);
    }

    public function historyForWilayah(Wilayah $wilayah, int $perPage = 20): LengthAwarePaginator
    {
        return $this->historyService->historyForWilayah($wilayah, $perPage);
    }
}
