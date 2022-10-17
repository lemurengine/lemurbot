<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\CustomDoc;

class CreateCustomDocRequest extends HiddenIdRequest
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
        return CustomDoc::$rules;
    }

}
