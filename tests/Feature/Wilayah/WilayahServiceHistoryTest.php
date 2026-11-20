<?php

use App\Models\Wilayah;
use App\Models\WilayahHistory;
use App\Services\Wilayah\WilayahHistoryService;
use App\Services\Wilayah\WilayahService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->service = app(WilayahService::class);
});
function createServiceHistoryWilayah(array $attributes = []): Wilayah
{
    static $counter = 0;
    $counter++;

    return Wilayah::create(array_merge([
        'kode' => '96'.str_pad((string) $counter, 11, '0', STR_PAD_LEFT),
        'nama' => 'Wilayah '.$counter,
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ], $attributes));
}
it('otomatis mencatat history ketika membuat wilayah', function () {
    $wilayah = $this->service->create([
        'kode' => '9500000000001',
        'nama' => 'Provinsi Test',
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ]);
    expect(WilayahHistory::where('wilayah_id', $wilayah->id)->count())->toBe(1)
        ->and(WilayahHistory::where('wilayah_id', $wilayah->id)->first()->action)->toBe(WilayahHistory::ACTION_CREATED);
});
it('otomatis mencatat perubahan wilayah', function () {
    $wilayah = createServiceHistoryWilayah();
    $this->service->update($wilayah, ['nama' => 'Nama Baru']);
    $history = WilayahHistory::where('wilayah_id', $wilayah->id)->firstOrFail();
    expect($history->action)->toBe(WilayahHistory::ACTION_UPDATED)
        ->and($history->old_values['nama'])->toBe($wilayah->nama)
        ->and($history->new_values['nama'])->toBe('Nama Baru');
});
it('tidak membuat history jika tidak ada perubahan', function () {
    $wilayah = createServiceHistoryWilayah();
    $this->service->update($wilayah, ['nama' => $wilayah->nama]);
    expect(WilayahHistory::where('wilayah_id', $wilayah->id)->count())->toBe(0);
});
it('otomatis mencatat penghapusan wilayah', function () {
    $wilayah = createServiceHistoryWilayah();
    $this->service->delete($wilayah);
    expect(WilayahHistory::where('wilayah_id', $wilayah->id)->first()->action)->toBe(WilayahHistory::ACTION_DELETED);
});
it('otomatis mencatat pemulihan wilayah', function () {
    $wilayah = createServiceHistoryWilayah();
    $wilayah->delete();
    $this->service->restore($wilayah->kode);
    expect(WilayahHistory::where('wilayah_id', $wilayah->id)->first()->action)->toBe(WilayahHistory::ACTION_RESTORED);
});
it('otomatis mencatat deaktivasi wilayah', function () {
    $wilayah = createServiceHistoryWilayah();
    $this->service->setActive($wilayah, false);
    expect(WilayahHistory::where('wilayah_id', $wilayah->id)->first()->action)->toBe(WilayahHistory::ACTION_DEACTIVATED);
});
it('tidak membuat history ketika status tidak berubah', function () {
    $wilayah = createServiceHistoryWilayah();
    $this->service->setActive($wilayah, true);
    expect(WilayahHistory::where('wilayah_id', $wilayah->id)->count())->toBe(0);
});
it('membatalkan perubahan wilayah jika history gagal disimpan', function () {
    $historyService = Mockery::mock(WilayahHistoryService::class);
    $historyService->shouldReceive('created')
        ->once()
        ->andThrow(new RuntimeException('History gagal disimpan.'));
    $this->app->instance(WilayahHistoryService::class, $historyService);
    $service = app(WilayahService::class);
    expect(fn () => $service->create([
        'kode' => '9500000000001',
        'nama' => 'Provinsi Test',
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ]))->toThrow(RuntimeException::class);
    expect(Wilayah::where('kode', '9500000000001')->exists())->toBeFalse();
});
it('menolak penghapusan wilayah yang memiliki anak terhapus', function () {
    $province = createServiceHistoryWilayah();
    Wilayah::create([
        'kode' => '9500000000001',
        'nama' => 'Kabupaten Test',
        'parent_code' => $province->kode,
        'level' => 2,
        'is_active' => true,
    ])->delete();
    expect(fn () => $this->service->delete($province))
        ->toThrow(ValidationException::class);
});
