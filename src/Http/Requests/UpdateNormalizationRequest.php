<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\Normalization;
use Illuminate\Validation\Rule;

class UpdateNormalizationRequest extends HiddenIdRequest
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
        $rules = Normalization::$rules;

        $rules['original_value'] = [
            'required',
            Rule::unique('normalizations')
                ->where('language_id', $this->language_id)
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
            'original_value.unique' => 'Duplicate record - this value has already been normalized for this language.',
        ];
    }
}
