<?php

declare(strict_types=1);

namespace Tests\Unit\Events;

use App\Events\RowRecordCreated;
use App\Models\Row;
use Tests\TestCase;

class RowRecordCreatingTest extends TestCase
{
    /** @test */
    public function correctEventGetters(): void
    {
        /** @var Row $model */
        $model = Row::factory()->make();
        $fileId = '/tmp_unique_id';
        $event = new RowRecordCreated($model, $fileId);

        $this->assertSame($model->id, $event->getModel()->id);
        $this->assertSame($fileId, $event->getFileId());
        $this->assertSame('App.Models.Row.public', $event->broadcastOn()->name);
        $this->assertSame($model->toArray(), $event->broadcastWith());
    }
}