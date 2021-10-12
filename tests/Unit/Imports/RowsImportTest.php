<?php

declare(strict_types=1);

namespace Tests\Unit\Imports;

use App\Events\RowRecordCreated;
use App\Imports\RowsImport;
use App\Models\Row;
use App\Services\ParsedFileRowsCountStorage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpOfficeDate;
use Tests\TestCase;

class RowsImportTest extends TestCase
{
    private string $fileId = 'unique_file_id';
    private RowsImport $dto;
    private array $excelRow  = [
        'id' => 14,
        'name' => 'Name',
        'date' => 44118.0,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->dto = new RowsImport($this->fileId);
    }

    /** @test */
    public function correctCreatingModel(): void
    {
        $this->dto->collection(collect([collect($this->excelRow)]));

        $createdModel = Row::query()->firstOrFail();
        $this->assertSame($this->excelRow['id'], $createdModel->id);
        $this->assertSame($this->excelRow['name'], $createdModel->name);

        $timestamp = PhpOfficeDate::excelToTimestamp($this->excelRow['date']);
        $isEqualDates = Carbon::createFromTimestamp($timestamp)
            ->equalTo($createdModel->date);
        $this->assertTrue($isEqualDates);
    }

    /** @test */
    public function eventFired(): void
    {
        Event::fake();

        $this->dto->collection(collect([collect($this->excelRow)]));

        Event::assertDispatched(RowRecordCreated::class);
    }

    /** @test */
    public function stateClearedAfterImportFinished(): void
    {
        $storage = $this->createMock(ParsedFileRowsCountStorage::class);
        $storage->expects($this->once())
            ->method('removeFileState');
        $this->app->bind(ParsedFileRowsCountStorage::class, fn() => $storage);

        /** @var AfterImport $event */
        $event = $this->app->make(AfterImport::class, ['importable' => $this->dto]);

        RowsImport::afterImport($event);
    }

    /** @test */
    public function correctDtoConfig(): void
    {
        $this->dto->collection(
            collect([
                collect($this->excelRow),
                collect($this->excelRow)
            ])
        );

        $this->assertSame(1000, $this->dto->chunkSize());
        $this->assertSame(1000, $this->dto->batchSize());
        $this->assertSame($this->fileId, $this->dto->getUniqueFileId());
        $this->assertSame(2, $this->dto->getRecordsCount());
    }
}