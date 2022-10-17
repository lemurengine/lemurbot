<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\Bot;
use Illuminate\Validation\Rule;

class UpdateBotRequest extends HiddenIdRequest
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

        $rules = Bot::$rules;

        $existingFilename = $this->input('image-filename');

        //we have have an existing filename we dont need to force the user to upload a file
        if (!empty($existingFilename)) {
            unset($rules['image']);
        }

        $rules['name'] = [
            'required',
            Rule::unique('bots')
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
            'name.unique' => 'Duplicate record - this bot name has already been taken.',
        ];
    }
}
