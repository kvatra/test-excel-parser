<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Row;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class RowControllerTest extends TestCase
{
    /** @test */
    public function uploadXmlFile(): void
    {
        Excel::fake();

        $file = UploadedFile::fake()->create('test.xlsx');

        $this->post(route('upload-xml'), ['xml' => $file])
            ->assertStatus(200);

        Excel::assertQueued($file->getFilename());
    }

    /** @test */
    public function fetchRows(): void
    {
        /** @var Row $firstRecord */
        $firstRecord = Row::factory()
            ->create(['date' => today()]);
        Row::factory()
            ->create(['date' => today()]);

        $responseData = $this->get(route('row-list'))
            ->assertStatus(200)
            ->viewData('groups');

        $this->assertCount(1, $responseData);

        $dateRecords = $responseData[today()->toDateString()];
        $this->assertCount(2, $dateRecords);

        $responseItem = Arr::first($dateRecords, fn (array $item) => $item['id'] === $firstRecord->id);
        $this->assertSame(['id' => $firstRecord->id, 'name' => $firstRecord->name], $responseItem);
    }
}