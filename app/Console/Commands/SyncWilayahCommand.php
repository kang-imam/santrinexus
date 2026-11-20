<?php

namespace App\Console\Commands;

use App\Services\Wilayah\WilayahSynchronizer;
use Illuminate\Console\Command;
use Throwable;

class SyncWilayahCommand extends Command
{
    protected $signature = 'wilayah:sync {--dry-run : Simulasi tanpa menyimpan perubahan} {--file= : Lokasi file SQL} {--chunk=500 : Jumlah data per batch}';

    protected $description = 'Sinkronisasi data wilayah dari sumber cahyadsn';

    public function handle(WilayahSynchronizer $synchronizer): int
    {
        $path = $this->option('file') ?: storage_path('app/data/wilayah.sql');
        $chunkSize = (int) $this->option('chunk');
        $dryRun = (bool) $this->option('dry-run');
        try {
            $this->info('Memulai sinkronisasi data wilayah...');
            $result = $synchronizer->sync($path, $dryRun, $chunkSize);
            $this->newLine();
            $this->table(
                ['Informasi', 'Jumlah'],
                [
                    ['Mode', $result['mode']],
                    ['Data sumber', number_format($result['source_total'])],
                    ['Data lokal', number_format($result['local_total'])],
                    ['Data baru', number_format($result['added'])],
                    ['Data diperbarui', number_format($result['updated'])],
                    ['Data tidak berubah', number_format($result['unchanged'])],
                    ['Data hilang', number_format($result['missing'])],
                    ['Dilewati karena soft delete', number_format($result['skipped_deleted'])],
                ]
            );
            if ($dryRun) {
                $this->warn('Mode dry-run: tidak ada perubahan database.');
            } else {
                $this->info('Sinkronisasi berhasil diterapkan.');
            }

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
