<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Redis\RedisManager;

class ParsedFileRowsCountStorage
{
    private RedisManager $redis;

    private const STORAGE_KEY = 'file_parsing_rows.';

    public function __construct(RedisManager $redis)
    {
        $this->redis = $redis;
    }

    public function increment(string $fileId): void
    {
        $this->redis->incr($this->generateKey($fileId));
    }

    public function removeFileState(string $fileId): void
    {
        $this->redis->del($this->generateKey($fileId));
    }

    private function generateKey(string $fileId): string
    {
        return self::STORAGE_KEY . $fileId;
    }
}