<?php

namespace App\Imports;

use App\Events\RowRecordCreating;
use App\Models\Row;
use App\Services\ParsedFileRowsCountStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;

class RowsImport implements ToModel, WithEvents, WithHeadingRow, WithCalculatedFormulas, WithChunkReading, WithUpserts, ShouldQueue
{
    use RegistersEventListeners;

    private string $uniqueFileId;

    public function __construct(string $uniqueFileId)
    {
        $this->uniqueFileId = $uniqueFileId;
    }

    public function model(array $row)
    {
        $model = new Row([
            'id' => $row['id'],
            'name' => $row['name'],
            'date' => Carbon::parse($row['date'])
        ]);

        event(new RowRecordCreating($model, $this->uniqueFileId));

        return $model;
    }

    public static function afterImport(AfterImport $event)
    {
        /** @var RowsImport $importable */
        $importable = $event->getConcernable();
        /** @var ParsedFileRowsCountStorage $storage */
        $storage = App::make(ParsedFileRowsCountStorage::class);

        $storage->removeFileState($importable->getUniqueFileId());
    }

    public function getUniqueFileId(): string
    {
        return $this->uniqueFileId;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function uniqueBy()
    {
        return 'id';
    }
}
