<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\FormRequests\UploadXmlRequest;
use App\Imports\RowsImport;
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
}