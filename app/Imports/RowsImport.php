<?php

namespace App\Imports;

use App\Events\RowRecordCreated;
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
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpOfficeDate;

class RowsImport implements ToModel, WithEvents, WithHeadingRow, WithCalculatedFormulas, WithChunkReading, ShouldQueue
{
    use RegistersEventListeners;

    private string $uniqueFileId;

    public function __construct(string $uniqueFileId)
    {
        $this->uniqueFileId = $uniqueFileId;
    }

    public function model(array $row)
    {
        $date = PhpOfficeDate::excelToTimestamp($row['date']);

        return new Row([
            'id' => $row['id'],
            'file_id' => $this->uniqueFileId,
            'name' => $row['name'],
            'date' => Carbon::createFromTimestamp($date),
        ]);
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

    public function chunkSize(): int
    {
        return 1000;
    }
}
