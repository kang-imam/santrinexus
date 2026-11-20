<?php

namespace App\Services\Wilayah;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use SplFileObject;

class WilayahImporter
{
    public function import(string $path, int $chunkSize = 500): array
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException("File SQL tidak ditemukan atau tidak dapat dibaca: {$path}");
        }
        if ($chunkSize < 1) {
            throw new RuntimeException('Chunk size harus lebih besar dari 0.');
        }

        return DB::transaction(function () use ($path, $chunkSize) {
            $file = new SplFileObject($path, 'r');
            $rows = [];
            $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $total = 0;
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
                    $parentCode = $level > 1
                        ? implode('.', array_slice($segments, 0, -1))
                        : null;
                    $timestamp = now();
                    $rows[] = [
                        'kode' => $code,
                        'nama' => str_replace("''", "'", $match[2]),
                        'parent_code' => $parentCode,
                        'level' => $level,
                        'is_active' => true,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                        'deleted_at' => null,
                    ];
                    $counts[$level]++;
                    $total++;
                    if (count($rows) >= $chunkSize) {
                        $this->insertChunk($rows);
                        $rows = [];
                    }
                }
            }
            if ($rows !== []) {
                $this->insertChunk($rows);
            }
            if ($total === 0) {
                throw new RuntimeException('Tidak ditemukan data wilayah pada file SQL.');
            }

            return [
                'total' => $total,
                'provinces' => $counts[1],
                'regencies' => $counts[2],
                'districts' => $counts[3],
                'villages' => $counts[4],
            ];
        });
    }

    private function insertChunk(array $rows): void
    {
        DB::table('wilayah')->upsert(
            $rows,
            ['kode'],
            ['nama', 'parent_code', 'level', 'updated_at']
        );
    }
}
