<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Redis\RedisManager;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var RedisManager $redis */
        $redis = $this->app->make(RedisManager::class);
        $redis->flushdb();
    }
}
