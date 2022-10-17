<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMapValueFileRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['upload_file'] = 'required|file|max:80000|mimes:csv,txt';
        $rules['processingOptions'] = 'required|in:append,delete';

        return $rules;
    }
}
