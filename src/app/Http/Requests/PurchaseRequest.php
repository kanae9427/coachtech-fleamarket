<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'item_id' => 'required|integer|exists:items,id',
            'shipping_postal_code' => 'required | regex:/^\d{3}-\d{4}$/',
            'shipping_address' => 'required',
            'shipping_building_name' => 'required',
            'payment_method' => 'required | in:credit_card,convenience_store',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.in' => '選択肢から支払い方法を選んでください',
        ];
    }
}
