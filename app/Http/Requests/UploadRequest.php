<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimetypes:application/json,text/json',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'The JSON file is required.',
            'file.file' => 'The uploaded file is not valid.',
            'file.mimetypes' => 'The file must be of type JSON.',
        ];
    }
}