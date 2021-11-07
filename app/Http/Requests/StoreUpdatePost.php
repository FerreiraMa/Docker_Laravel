<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreUpdatePost extends FormRequest
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
        $id = $s = preg_replace('/[^0-9]/', '', $this->segment('1'));
        // dd($id);
        $rules = [

            'title' => [
                'required',
                'min:3',
                'max:160',
                //'unique:posts, title,{id},id',
                Rule::unique('posts')->ignore($id)
            ],
            'content' => ['nullable', 'min:5', 'max:1000'],
            'image' => ['required', 'image']
        ];

        if ($this->method() == 'PUT') {
                $rules['image'] = ['nullable', 'image'];
        }

        return $rules;
    }
}
