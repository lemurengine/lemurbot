<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\Section;
use LemurEngine\LemurBot\Models\Set;
use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\Map;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
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
        $rules = Section::$rules;

        $rules['name'] = [
            'required',
            Rule::unique('sections')
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
            'name.unique' => 'Duplicate record - this name is already taken.',
        ];
    }
}
