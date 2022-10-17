<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\BotCategoryGroup;
use Illuminate\Validation\Rule;

class CreateBotCategoryGroupRequest extends HiddenIdRequest
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
        $rules = BotCategoryGroup::$rules;
        //if this is a bulk insert we will not validate uniqueness here
        if (!empty($this->input('bulk'))) {
            return $rules;
        }

        $rules['category_group_id'] = [
            'required',
            Rule::unique('bot_category_groups')
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
            'category_group_id.unique' => 'Duplicate record - this category group is already linked to this bot.',
        ];
    }
}
