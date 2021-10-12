<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\FormRequests\UploadXmlRequest;
use App\Imports\RowsImport;
use App\Models\Row;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class RowController extends Controller
{
    public function uploadXmlFile(UploadXmlRequest $request)
    {
        /** @var UploadedFile $file */
        $file = $request->validated()['xml'];
        Excel::import(new RowsImport($file->getPath()), $file);

        return response()->json('Success!');
    }

    public function fetchRows()
    {
        $data = Row::all()
            ->groupBy(fn (Row $row) => $row->date->toDateString())
            ->map(function (Collection $group) {
                return $group->map->only(['id', 'name']);
            });

        return view('row_list', ['groups' => $data->toArray()]);
    }
}