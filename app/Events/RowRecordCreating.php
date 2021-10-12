<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Row;

class RowRecordCreating
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
}