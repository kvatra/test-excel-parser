<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RowRecordCreated;
use App\Services\ParsedFileRowsCountStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class IncrementParsedFileRowsCount implements ShouldQueue
{
    private ParsedFileRowsCountStorage $storage;

    public function __construct(ParsedFileRowsCountStorage $storage)
    {
        $this->storage = $storage;
    }

    public function handle(RowRecordCreated $event)
    {
        $this->storage->increment($event->getModel()->file_id);
    }
}