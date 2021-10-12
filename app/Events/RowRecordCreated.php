<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Row;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RowRecordCreated implements ShouldBroadcast
{
    private Row $model;
    private string $fileId;

    public function __construct(Row $model, string $fileId)
    {
        $this->model = $model;
        $this->fileId = $fileId;
    }

    public function getModel(): Row
    {
        return $this->model;
    }

    public function getFileId(): string
    {
        return $this->fileId;
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