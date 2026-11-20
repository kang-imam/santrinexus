<?php

namespace App\Services\Wilayah;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use SplFileObject;

class WilayahSynchronizer
{
    public function sync(string $path, bool $dryRun = true, int $chunkSize = 500): array
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException("File SQL tidak ditemukan atau tidak dapat dibaca: {$path}");
        }
        if ($chunkSize < 1) {
            throw new RuntimeException('Chunk size harus lebih besar dari 0.');
        }
        $localTotal = DB::table('wilayah')->count();
        $counts = [
            'added' => 0,
            'updated' => 0,
            'unchanged' => 0,
            'skipped_deleted' => 0,
            'matched' => 0,
            'source_total' => 0,
        ];
        $process = function () use ($path, $chunkSize, &$counts, $dryRun) {
            $file = new SplFileObject($path, 'r');
            $rows = [];
            $timestamp = null;
            while (! $file->eof()) {
                $line = $file->fgets();
                if (! preg_match_all("/\\('((?:''|[^'])*)','((?:''|[^'])*)'\\)/", $line, $matches, PREG_SET_ORDER)) {
                    continue;
                }
                foreach ($matches as $match) {
                    $code = $match[1];
                    $segments = explode('.', $code);
                    $lengths = array_map('strlen', $segments);
                    $level = match ($lengths) {
                        [2] => 1,
                        [2, 2] => 2,
                        [2, 2, 2] => 3,
                        [2, 2, 2, 4] => 4,
                        default => throw new RuntimeException("Format kode wilayah tidak valid: {$code}")
                    };
                    if ($timestamp === null) {
                        $timestamp = now()->toDateTimeString();
                    }
                    $rows[] = [
                        'kode' => $code,
                        'nama' => str_replace("''", "'", $match[2]),
                        'parent_code' => $level > 1 ? implode('.', array_slice($segments, 0, -1)) : null,
                        'level' => $level,
                        'is_active' => true,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                        'deleted_at' => null,
                    ];
                    $counts['source_total']++;
                    if (count($rows) >= $chunkSize) {
                        $this->processChunk($rows, $counts, $dryRun);
                        $rows = [];
                        $timestamp = null;
                    }
                }
            }
            if ($rows !== []) {
                $this->processChunk($rows, $counts, $dryRun);
            }
            if ($counts['source_total'] === 0) {
                throw new RuntimeException('Tidak ditemukan data wilayah pada file SQL.');
            }
        };
        if ($dryRun) {
            $process();
        } else {
            DB::transaction($process);
        }

        return [
            'mode' => $dryRun ? 'dry-run' : 'sync',
            'source_total' => $counts['source_total'],
            'local_total' => $localTotal,
            'added' => $counts['added'],
            'updated' => $counts['updated'],
            'unchanged' => $counts['unchanged'],
            'missing' => max(0, $localTotal - $counts['matched']),
            'skipped_deleted' => $counts['skipped_deleted'],
            'applied' => ! $dryRun,
        ];
    }

    private function processChunk(array $rows, array &$counts, bool $dryRun): void
    {
        $codes = array_column($rows, 'kode');
        $existing = DB::table('wilayah')
            ->whereIn('kode', $codes)
            ->get(['kode', 'nama', 'parent_code', 'level', 'deleted_at'])
            ->keyBy('kode');
        $newRows = [];
        $updateRows = [];
        $counts['matched'] += $existing->count();
        foreach ($rows as $row) {
            $current = $existing->get($row['kode']);
            if (! $current) {
                $newRows[] = $row;
                $counts['added']++;

                continue;
            }
            if ($current->deleted_at !== null) {
                $counts['skipped_deleted']++;

                continue;
            }
            $changed = $current->nama !== $row['nama']
                || $current->parent_code !== $row['parent_code']
                || (int) $current->level !== $row['level'];
            if (! $changed) {
                $counts['unchanged']++;

                continue;
            }
            $updateRows[] = $row;
            $counts['updated']++;
        }
        if (! $dryRun) {
            if ($newRows !== []) {
                DB::table('wilayah')->insert($newRows);
            }
            if ($updateRows !== []) {
                DB::table('wilayah')->upsert(
                    $updateRows,
                    ['kode'],
                    ['nama', 'parent_code', 'level', 'updated_at']
                );
            }
        }
    }
}
