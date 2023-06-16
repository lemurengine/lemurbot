<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Validation\Rule;
use LemurEngine\LemurBot\Models\BotPlugin;

class CreateBotPluginRequest extends HiddenIdRequest
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
        $rules = BotPlugin::$rules;

        $rules['plugin_id'] = [
            'required',
            Rule::unique('bot_plugins')
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
            'plugin_id.unique' => 'Duplicate record - this plugin is already linked to this bot.',
        ];
    }
}
