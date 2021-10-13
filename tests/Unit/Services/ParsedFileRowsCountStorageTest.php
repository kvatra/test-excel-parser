<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\ParsedFileRowsCountStorage;
use Illuminate\Redis\RedisManager;
use Tests\TestCase;

class ParsedFileRowsCountStorageTest extends TestCase
{
    /** @test */
    public function callRedisManager(): void
    {
        $fileId = 'unique_file_id';
        $redisKey = "file_parsing_rows.{$fileId}";

        /** @var ParsedFileRowsCountStorage $storage */
        $storage = $this->app->make(ParsedFileRowsCountStorage::class);
        /** @var RedisManager $redis */
        $redis = $this->app->make(RedisManager::class);

        $this->assertNull($redis->get($redisKey));

        $storage->increment($fileId);
        $this->assertSame('1', $redis->get($redisKey));

        $storage->removeFileState($fileId);
        $this->assertNull($redis->get($redisKey));
    }
}