<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\BotAllowedSite;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBotAllowedSiteRequest extends FormRequest
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

        $rules = BotAllowedSite::$rules;

        return $rules;
    }

}
