<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\BotKey;
use Illuminate\Validation\Rule;

class UpdateBotKeyRequest extends HiddenIdRequest
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
        $rules = BotKey::$rules;

        $rules['name'] = [
            'required',
            Rule::unique('bot_keys')
                ->ignore($this->slug, 'slug')
                ->where('bot_id', $this->bot_id)
                ->whereNull('deleted_at')
        ];

        $rules['value'] = [
            'required',
            Rule::unique('bot_keys')
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
            'name.unique' => 'Duplicate record - this key name has already been taken.',
            'value.unique' => 'Duplicate record - this key has already been taken.',
        ];
    }
}
