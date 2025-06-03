<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required',
            'price' => 'required | integer | min:0',
            'image' => 'required | mimes:jpeg,png',
            'category_id' => 'required | array | min:1',
            'category_id.*' => 'exists:categories,id',
            'condition' => 'required',
            'brand_name' => 'nullable',
            'description' => 'required | max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '数字で入力してください',
            'price.min' => '販売価格を入力してください',
            'image.required' => '商品画像を選択してください',
            'image.mimes' => '画像には jpeg または png ファイルを指定してください',
            'category_id.required' => 'カテゴリーを選択してください',
            'category_id.min' => 'カテゴリーを選択してください',
            'category_id.*.exists' => '選択したカテゴリーが存在しません',
            'condition.required' => '商品の状態を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.min' => '商品説明を255文字以内で入力してください'
        ];
    }
}
