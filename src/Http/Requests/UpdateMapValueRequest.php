<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\MapValue;
use Illuminate\Validation\Rule;

class UpdateMapValueRequest extends HiddenIdRequest
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
        $rules = MapValue::$rules;

        $rules['word'] = [
            'required',
            Rule::unique('map_values')
                ->where('map_id', $this->map_id)
                ->ignore($this->slug, 'slug')
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
            'word.unique' => 'Duplicate record - this word is already mapped in this map.',
        ];
    }
}
