<?php

namespace App\Imports;

use App\Events\RowRecordCreated;
use App\Models\Row;
use App\Services\ParsedFileRowsCountStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Jobs\ReadChunk;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpOfficeDate;

class RowsImport implements ToCollection, WithEvents, WithHeadingRow, WithCalculatedFormulas, WithChunkReading, ShouldQueue
{
    use RegistersEventListeners;

    private string $uniqueFileId;
    private int $recordsCount;

    public function __construct(string $uniqueFileId)
    {
        $this->uniqueFileId = $uniqueFileId;
    }

    public function collection(Collection $collection)
    {
        $insertValues = $collection->map(function (Collection $row) {
            $date = PhpOfficeDate::excelToTimestamp($row['date']);

            return [
                'id' => $row['id'],
                'name' => $row['name'],
                'date' => Carbon::createFromTimestamp($date)->toDateString(),
            ];
        });

        $this->recordsCount = $insertValues->count();

        Row::query()->upsert($insertValues->toArray(), 'id');

        $insertValues->each(function (array $modelAttributes) {
            event(new RowRecordCreated(new Row($modelAttributes), $this->uniqueFileId));
        });
    }

    public static function afterSheet(AfterSheet $event)
    {
        /** @var RowsImport $importable */
        $importable = $event->getConcernable();
        /** @var ParsedFileRowsCountStorage $storage */
        $storage = App::make(ParsedFileRowsCountStorage::class);

        $storage->increment($importable->getUniqueFileId(), $importable->getRecordsCount());
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
        return 1000;
    }

    public function getRecordsCount(): int
    {
        return $this->recordsCount;
    }
}
