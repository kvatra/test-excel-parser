<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RowRecordCreating;
use App\Services\ParsedFileRowsCountStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class IncrementParsedFileRowsCount implements ShouldQueue
{
    public function handle(RowRecordCreating $event)
    {
        /** @var ParsedFileRowsCountStorage $storage */
        $storage = App::make(ParsedFileRowsCountStorage::class);

        $storage->increment($event->getFileId());
    }
}