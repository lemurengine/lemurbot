<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Validation\Rule;
use LemurEngine\LemurBot\Models\SetValue;

class CreateSetValueRequest extends HiddenIdRequest
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
        $rules = SetValue::$rules;

        $rules['value'] = [
            'required',
            Rule::unique('set_values')
                ->where('set_id', $this->set_id)
                ->whereNull('deleted_at')
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'value.unique' => 'Duplicate record - this value is already belongs to this set.',
        ];
    }
}
