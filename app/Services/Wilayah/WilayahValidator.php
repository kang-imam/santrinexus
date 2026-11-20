<?php

namespace App\Services\Wilayah;

use App\Models\Wilayah;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WilayahValidator
{
    public function validateForCreate(array $data): array
    {
        return $this->validate($data);
    }

    public function validateForUpdate(Wilayah $wilayah, array $data): array
    {
        $attributes = array_merge($wilayah->only([
            'kode',
            'nama',
            'parent_code',
            'level',
            'is_active',
        ]), $data);
        $validated = $this->validate($attributes, $wilayah);
        if (isset($data['kode']) && $data['kode'] !== $wilayah->kode && $wilayah->children()->exists()) {
            throw ValidationException::withMessages([
                'kode' => 'Kode tidak dapat diubah karena wilayah masih memiliki wilayah turunan.',
            ]);
        }

        return array_intersect_key($validated, $data);
    }

    private function validate(array $data, ?Wilayah $current = null): array
    {
        $validator = Validator::make($data, [
            'kode' => [
                'required',
                'string',
                'max:13',
                Rule::unique('wilayah', 'kode')->ignore($current?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'parent_code' => ['nullable', 'string', 'max:13'],
            'level' => ['required', 'integer', 'between:1,4'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $validator->after(function ($validator) use ($data, $current) {
            $level = (int) ($data['level'] ?? 0);
            $parentCode = $data['parent_code'] ?? null;
            if ($level === 1 && $parentCode !== null) {
                $validator->errors()->add('parent_code', 'Provinsi tidak boleh memiliki wilayah induk.');

                return;
            }
            if ($level > 1 && ! $parentCode) {
                $validator->errors()->add('parent_code', 'Wilayah ini wajib memiliki wilayah induk.');

                return;
            }
            if ($level === 1) {
                return;
            }
            $parent = Wilayah::where('kode', $parentCode)->first();
            if (! $parent) {
                $validator->errors()->add('parent_code', 'Wilayah induk tidak ditemukan atau sudah dihapus.');

                return;
            }
            if ($parent->level !== $level - 1) {
                $validator->errors()->add('parent_code', 'Tingkat wilayah induk tidak sesuai.');
            }
            if ($current && $parentCode === $current->kode) {
                $validator->errors()->add('parent_code', 'Wilayah tidak boleh menjadi induk dirinya sendiri.');
            }
            if ($current && $this->isDescendant($parentCode, $current->kode)) {
                $validator->errors()->add('parent_code', 'Wilayah tidak boleh dipindahkan ke dalam turunannya sendiri.');
            }
        });

        return $validator->validate();
    }

    private function isDescendant(string $possibleDescendant, string $ancestorCode): bool
    {
        $parent = Wilayah::where('kode', $possibleDescendant)->first();
        $visited = [];
        while ($parent && ! in_array($parent->kode, $visited, true)) {
            if ($parent->kode === $ancestorCode) {
                return true;
            }
            $visited[] = $parent->kode;
            $parent = $parent->parent;
        }

        return false;
    }
}
