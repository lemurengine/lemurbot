<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\Language;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
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
        $rules = Language::$rules;

        $rules['name'] = [
            'required',
            Rule::unique('languages')
                ->ignore($this->slug, 'slug')
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Duplicate record - this language name has already been taken.',
        ];
    }
}
