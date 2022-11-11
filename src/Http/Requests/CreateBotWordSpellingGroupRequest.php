<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Validation\Rule;
use LemurEngine\LemurBot\Models\BotWordSpellingGroup;

class CreateBotWordSpellingGroupRequest extends HiddenIdRequest
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
        $rules = BotWordSpellingGroup::$rules;
        //if this is a bulk insert we will not validate uniqueness here
        if (!empty($this->input('bulk'))) {
            return $rules;
        }

        $rules['word_spelling_group_id'] = [
            'required',
            Rule::unique('word_spelling_groups')
                ->where('bot_id', $this->bot_id)
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
            'word_spelling_group_id.unique' =>
                'Duplicate record - this word spelling group is already linked to this bot.',
        ];
    }
}
