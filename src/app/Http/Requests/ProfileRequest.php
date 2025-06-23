<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        return [
            'icon' => 'nullable | mimes:jpeg,png',
        ];
    }
    public function messages()
    {
        return [
            'icon.mimes' => '画像には jpeg または png ファイルを指定してください',
        ];
    }
}
