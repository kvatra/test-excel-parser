<?php

declare(strict_types=1);

namespace Tests\Unit\Events;

use App\Events\RowRecordCreated;
use App\Models\Row;
use Tests\TestCase;

class RowRecordCreatedTest extends TestCase
{
    /** @test */
    public function correctEventGetters(): void
    {
        /** @var Row $model */
        $model = Row::factory()->make();
        $event = new RowRecordCreated($model);

        $this->assertSame($model->id, $event->getModel()->id);
        $this->assertSame('App.Models.Row.public', $event->broadcastOn()->name);
        $this->assertSame($model->toArray(), $event->broadcastWith());
    }
}