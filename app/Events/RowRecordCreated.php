<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Row;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RowRecordCreated implements ShouldBroadcast
{
    private Row $model;

    public function __construct(Row $model)
    {
        $this->model = $model;
    }

    public function getModel(): Row
    {
        return $this->model;
    }

    public function broadcastOn()
    {
        return new Channel('App.Models.Row.public');
    }

    public function broadcastWith(): array
    {
        return $this->model->toArray();
    }
}