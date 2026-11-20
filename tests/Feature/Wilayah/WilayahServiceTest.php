<?php

use App\Models\Wilayah;
use App\Services\Wilayah\WilayahService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->service = app(WilayahService::class);
});
function createWilayah(array $attributes = []): Wilayah
{
    static $counter = 0;
    $counter++;

    return Wilayah::create(array_merge([
        'kode' => '99'.str_pad((string) $counter, 11, '0', STR_PAD_LEFT),
        'nama' => 'Wilayah '.$counter,
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ], $attributes));
}
it('menampilkan daftar wilayah dengan pagination', function () {
    createWilayah();
    createWilayah();
    $result = $this->service->paginate();
    expect($result->total())->toBe(2);
});
it('memfilter wilayah berdasarkan level', function () {
    $province = createWilayah();
    createWilayah([
        'kode' => '9900000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
    ]);
    $result = $this->service->paginate(['level' => 2]);
    expect($result->total())->toBe(1)
        ->and($result->first()->level)->toBe(2);
});
it('mencari wilayah berdasarkan nama', function () {
    createWilayah(['nama' => 'Jawa Contoh']);
    createWilayah(['nama' => 'Sumatera Contoh']);
    $result = $this->service->paginate(['search' => 'Jawa']);
    expect($result->total())->toBe(1)
        ->and($result->first()->nama)->toBe('Jawa Contoh');
});
it('menemukan wilayah berdasarkan kode beserta relasinya', function () {
    $province = createWilayah();
    createWilayah([
        'kode' => '9900000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
    ]);
    $result = $this->service->findByCode($province->kode);
    expect($result)->not->toBeNull()
        ->and($result->children)->toHaveCount(1);
});
it('mengambil daftar provinsi aktif', function () {
    createWilayah();
    createWilayah(['is_active' => false]);
    expect($this->service->getProvinces())->toHaveCount(1);
});
it('mengambil kabupaten berdasarkan provinsi', function () {
    $province = createWilayah();
    createWilayah([
        'kode' => '9900000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
    ]);
    expect($this->service->getRegencies($province->kode))->toHaveCount(1);
});
it('membuat wilayah dengan induk yang valid', function () {
    $province = createWilayah();
    $regency = $this->service->create([
        'kode' => '9900000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
        'is_active' => true,
    ]);
    expect($regency)->toBeInstanceOf(Wilayah::class)
        ->and($regency->parent_code)->toBe($province->kode);
});
it('menolak pembuatan wilayah dengan induk yang tidak ditemukan', function () {
    expect(fn () => $this->service->create([
        'kode' => '9900000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => '9800000000000',
        'level' => 2,
    ]))->toThrow(ValidationException::class);
});
it('memperbarui nama wilayah', function () {
    $wilayah = createWilayah();
    $updated = $this->service->update($wilayah, ['nama' => 'Nama Baru']);
    expect($updated->nama)->toBe('Nama Baru');
});
it('menolak penghapusan wilayah yang masih memiliki anak', function () {
    $province = createWilayah();
    createWilayah([
        'kode' => '9900000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
    ]);
    expect(fn () => $this->service->delete($province))
        ->toThrow(ValidationException::class);
});
it('menghapus wilayah menggunakan soft delete', function () {
    $wilayah = createWilayah();
    expect($this->service->delete($wilayah))->toBeTrue()
        ->and(Wilayah::withTrashed()->find($wilayah->id)->trashed())->toBeTrue();
});
it('mengembalikan wilayah yang sudah dihapus', function () {
    $wilayah = createWilayah();
    $wilayah->delete();
    expect($this->service->restore($wilayah->kode))->toBeTrue()
        ->and(Wilayah::where('kode', $wilayah->kode)->exists())->toBeTrue();
});
it('mengubah status aktif wilayah', function () {
    $wilayah = createWilayah();
    $updated = $this->service->setActive($wilayah, false);
    expect($updated->is_active)->toBeFalse();
});
