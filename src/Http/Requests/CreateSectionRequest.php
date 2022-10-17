<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class CreateSectionRequest extends FormRequest
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
        return Section::$rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Duplicate record - this name is already taken.',
        ];
    }
}
