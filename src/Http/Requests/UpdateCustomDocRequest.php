<?php

namespace LemurEngine\LemurBot\Http\Requests;

use LemurEngine\LemurBot\Models\CustomDoc;

class UpdateCustomDocRequest extends HiddenIdRequest
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
        //is this a request to restore this item?
        //if so we dont need any rules...
        //as we are just going to un-delete it
        if($this->input('restore',0)){
            return [];
        }

        return CustomDoc::$rules;
    }
}
