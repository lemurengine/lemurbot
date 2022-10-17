<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\WordSpellingGroup;

class CreateWordSpellingGroupRequest extends HiddenIdRequest
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
        return WordSpellingGroup::$rules;
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
