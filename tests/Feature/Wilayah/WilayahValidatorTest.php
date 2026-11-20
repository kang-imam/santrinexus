<?php

use App\Models\Wilayah;
use App\Services\Wilayah\WilayahValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->validator = app(WilayahValidator::class);
});
function createValidatorWilayah(array $attributes = []): Wilayah
{
    static $counter = 0;
    $counter++;

    return Wilayah::create(array_merge([
        'kode' => '98'.str_pad((string) $counter, 11, '0', STR_PAD_LEFT),
        'nama' => 'Wilayah '.$counter,
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ], $attributes));
}
it('memvalidasi data provinsi', function () {
    $data = $this->validator->validateForCreate([
        'kode' => '9700000000001',
        'nama' => 'Provinsi Test',
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ]);
    expect($data['nama'])->toBe('Provinsi Test');
});
it('menolak kode wilayah duplikat', function () {
    $wilayah = createValidatorWilayah();
    expect(fn () => $this->validator->validateForCreate([
        'kode' => $wilayah->kode,
        'nama' => 'Provinsi Baru',
        'parent_code' => null,
        'level' => 1,
    ]))->toThrow(ValidationException::class);
});
it('menolak provinsi yang memiliki induk', function () {
    $parent = createValidatorWilayah();
    expect(fn () => $this->validator->validateForCreate([
        'kode' => '9700000000001',
        'nama' => 'Provinsi Test',
        'parent_code' => $parent->kode,
        'level' => 1,
    ]))->toThrow(ValidationException::class);
});
it('menolak wilayah tanpa induk', function () {
    expect(fn () => $this->validator->validateForCreate([
        'kode' => '9700000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => null,
        'level' => 2,
    ]))->toThrow(ValidationException::class);
});
it('menolak induk dengan level yang tidak sesuai', function () {
    $province = createValidatorWilayah();
    expect(fn () => $this->validator->validateForCreate([
        'kode' => '9700000000001',
        'nama' => 'Kecamatan Test',
        'parent_code' => $province->kode,
        'level' => 3,
    ]))->toThrow(ValidationException::class);
});
it('memvalidasi pembaruan sebagian data', function () {
    $wilayah = createValidatorWilayah();
    $data = $this->validator->validateForUpdate($wilayah, [
        'nama' => 'Nama Baru',
    ]);
    expect($data)->toBe(['nama' => 'Nama Baru']);
});
it('menolak perubahan kode jika wilayah memiliki anak', function () {
    $province = createValidatorWilayah();
    Wilayah::create([
        'kode' => '9700000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
        'is_active' => true,
    ]);
    expect(fn () => $this->validator->validateForUpdate($province, [
        'kode' => '9600000000001',
    ]))->toThrow(ValidationException::class);
});
it('menolak wilayah yang dipindahkan ke dalam turunannya', function () {
    $province = createValidatorWilayah();
    $regency = Wilayah::create([
        'kode' => '9700000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
        'is_active' => true,
    ]);
    expect(fn () => $this->validator->validateForUpdate($province, [
        'parent_code' => $regency->kode,
    ]))->toThrow(ValidationException::class);
});
