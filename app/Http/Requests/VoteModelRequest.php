<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

use Auth;

class VoteModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'start' => 'required|
                date_format:"Y-m-d H:i:s"|before:end',
            'end' => 'required|date_format:"Y-m-d H:i:s"'
        ];
    }
}
