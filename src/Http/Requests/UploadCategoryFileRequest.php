<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\WordTransformation;

class UploadCategoryFileRequest extends FormRequest
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
        //had to add html to the list because it was getting confused html e.g. <a> where in the aiml/csv files
        $rules['aiml_file'] = 'required|max:80000|file|mimes:application/xml,xml,csv,txt,html';
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $mineType = $this->file('aiml_file')->getMimeType();
        return [
            'aiml_file.mimes' => 'Incorrect mime type - please make sure your file has one of the following mime types: application/xml,xml,csv,txt.
                                    It is '.$mineType,
        ];
    }
}
