<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Events\RowRecordCreated;
use App\Listeners\IncrementParsedFileRowsCount;
use App\Models\Row;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class IncrementParsedFileRowsCountTest extends TestCase
{
    /** @test */
    public function incrementedState()
    {
        /** @var RedisManager $redis */
        $redis = App::make(RedisManager::class);

        /** @var Row $model */
        $model = Row::factory()->make();
        $redisKey = "file_parsing_rows.$model->file_id";
        $event = new RowRecordCreated($model);
        /** @var IncrementParsedFileRowsCount $listener */
        $listener = App::make(IncrementParsedFileRowsCount::class);

        $this->assertNull($redis->get($redisKey));
        $listener->handle($event);

        $this->assertSame('1', $redis->get($redisKey));
    }
}