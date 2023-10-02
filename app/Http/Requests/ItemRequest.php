<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'itemName' => 'required | string | max:255',
            'price' => 'required | integer',
            'grandchild_category_id' => 'required | integer | min:1',
            'brand' => 'required | string | max:255',
            'condition' => 'required | integer',
            'description' => 'required | text',
        ];
    }
}
