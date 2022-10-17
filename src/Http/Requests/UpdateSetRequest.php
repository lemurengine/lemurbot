<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\SetValue;
use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\Set;
use Illuminate\Validation\Rule;

class UpdateSetRequest extends FormRequest
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
        $rules = Set::$rules;

        $rules['name'] = [
            'required',
            Rule::unique('sets')
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
