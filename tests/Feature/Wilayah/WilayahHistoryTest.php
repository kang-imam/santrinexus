<?php

use App\Models\User;
use App\Models\Wilayah;
use App\Models\WilayahHistory;
use App\Services\Wilayah\WilayahHistoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->service = app(WilayahHistoryService::class);
});
function createHistoryWilayah(array $attributes = []): Wilayah
{
    static $counter = 0;
    $counter++;

    return Wilayah::create(array_merge([
        'kode' => '97'.str_pad((string) $counter, 11, '0', STR_PAD_LEFT),
        'nama' => 'Wilayah '.$counter,
        'parent_code' => null,
        'level' => 1,
        'is_active' => true,
    ], $attributes));
}
it('mencatat pembuatan wilayah', function () {
    $wilayah = createHistoryWilayah();
    $history = $this->service->created($wilayah);
    expect($history->action)->toBe(WilayahHistory::ACTION_CREATED)
        ->and($history->kode)->toBe($wilayah->kode)
        ->and($history->new_values['nama'])->toBe($wilayah->nama);
});
it('mencatat perubahan wilayah', function () {
    $wilayah = createHistoryWilayah();
    $history = $this->service->updated(
        $wilayah,
        ['nama' => 'Nama Lama'],
        ['nama' => 'Nama Baru']
    );
    expect($history->action)->toBe(WilayahHistory::ACTION_UPDATED)
        ->and($history->old_values['nama'])->toBe('Nama Lama')
        ->and($history->new_values['nama'])->toBe('Nama Baru');
});
it('tidak mencatat perubahan jika data baru kosong', function () {
    $wilayah = createHistoryWilayah();
    expect($this->service->updated($wilayah, [], []))->toBeNull()
        ->and(WilayahHistory::count())->toBe(0);
});
it('mencatat penghapusan wilayah', function () {
    $wilayah = createHistoryWilayah();
    $history = $this->service->deleted($wilayah);
    expect($history->action)->toBe(WilayahHistory::ACTION_DELETED)
        ->and($history->old_values['nama'])->toBe($wilayah->nama);
});
it('mencatat pemulihan wilayah', function () {
    $wilayah = createHistoryWilayah();
    $history = $this->service->restored($wilayah);
    expect($history->action)->toBe(WilayahHistory::ACTION_RESTORED)
        ->and($history->new_values['nama'])->toBe($wilayah->nama);
});
it('mencatat aktivasi wilayah', function () {
    $wilayah = createHistoryWilayah();
    $history = $this->service->activated($wilayah);
    expect($history->action)->toBe(WilayahHistory::ACTION_ACTIVATED)
        ->and($history->old_values['is_active'])->toBeFalse()
        ->and($history->new_values['is_active'])->toBeTrue();
});
it('mencatat deaktivasi wilayah', function () {
    $wilayah = createHistoryWilayah();
    $history = $this->service->deactivated($wilayah);
    expect($history->action)->toBe(WilayahHistory::ACTION_DEACTIVATED)
        ->and($history->old_values['is_active'])->toBeTrue()
        ->and($history->new_values['is_active'])->toBeFalse();
});
it('menyimpan identitas pengguna yang melakukan perubahan', function () {
    $user = User::factory()->create();
    Auth::login($user);
    $wilayah = createHistoryWilayah();
    $history = $this->service->created($wilayah);
    expect($history->user_id)->toBe($user->id);
});
it('mengambil riwayat berdasarkan kode wilayah', function () {
    $wilayah = createHistoryWilayah();
    $this->service->created($wilayah);
    $this->service->deleted($wilayah);
    $result = $this->service->history($wilayah->kode);
    expect($result->total())->toBe(2)
        ->and($result->items()[0]->action)->toBe(WilayahHistory::ACTION_DELETED);
});
it('membatasi pagination maksimal 100 data', function () {
    $wilayah = createHistoryWilayah();
    foreach (range(1, 105) as $index) {
        $this->service->record($wilayah, WilayahHistory::ACTION_UPDATED);
    }
    $result = $this->service->history($wilayah->kode, 200);
    expect($result->perPage())->toBe(100)
        ->and($result->total())->toBe(105);
});
it('mengambil snapshot data wilayah', function () {
    $wilayah = createHistoryWilayah();
    $snapshot = $this->service->snapshot($wilayah);
    expect($snapshot)->toHaveKeys([
        'kode',
        'nama',
        'parent_code',
        'level',
        'is_active',
    ]);
});
