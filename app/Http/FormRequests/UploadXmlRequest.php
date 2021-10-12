<?php

declare(strict_types=1);

namespace App\Http\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class UploadXmlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'xml' => 'required|file',
        ];
    }
}