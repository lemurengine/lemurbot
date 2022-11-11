<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LemurEngine\LemurBot\Models\WordSpelling;

class UpdateWordSpellingRequest extends HiddenIdRequest
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
        $rules = WordSpelling::$rules;
        $rules['word'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('word_spellings')
                ->where('word_spelling_group_id', $this->word_spelling_group_id)
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
            'word.unique' =>
                'Duplicate record - a correction already exists for this word in this group.',
        ];
    }

}
