<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wilayah extends Model
{
    use SoftDeletes;

    protected $table = 'wilayah';

    protected $fillable = [
        'kode',
        'nama',
        'parent_code',
        'level',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_code', 'kode');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_code', 'kode');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeProvince(Builder $query): Builder
    {
        return $query->where('level', 1);
    }

    public function scopeRegency(Builder $query): Builder
    {
        return $query->where('level', 2);
    }

    public function scopeDistrict(Builder $query): Builder
    {
        return $query->where('level', 3);
    }

    public function scopeVillage(Builder $query): Builder
    {
        return $query->where('level', 4);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('nama', 'like', "%{$search}%");
    }

    public function getFullNameAttribute(): string
    {
        return $this->parent
            ? $this->parent->full_name.', '.$this->nama
            : $this->nama;
    }

    public function histories(): HasMany
    {
        return $this->hasMany(WilayahHistory::class);
    }
}
