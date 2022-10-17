<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\Set;

class CreateSetRequest extends FormRequest
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
        return Set::$rules;
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
