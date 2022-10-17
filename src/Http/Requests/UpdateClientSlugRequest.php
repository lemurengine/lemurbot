<?php

namespace LemurEngine\LemurBot\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LemurEngine\LemurBot\Models\User;
use Illuminate\Validation\Rule;

class UpdateClientSlugRequest extends FormRequest
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

        //we have to redirect to the datatable
        //all other attempt to redirect to the edit page
        //with the correct data prepopulated has failed
        //so this is the safest option until i can work out how
        $this->redirectRoute = 'clients.index';


        $rules['slug'] = [
            'required',
            'unique:clients'
        ];

        $rules['original_slug'] = [
            'required'
        ];

        return $rules;
    }
}
