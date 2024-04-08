<?php

namespace App\Http\Requests\User;

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
            'name' => ['required|string|max:255'],
            'email' => ['required|string|email|max:255|unique:users,email,' . auth()->id()],
            'password' => ['nullable|string|min:8|confirmed'],
            'introduction' => ['nullable|string|max:50'],
        ];

        // アイコン画像がアップロードされた場合のみバリデーションルールを適用
        if ($this->hasFile('icon')) {
            $rules['icon'] = ['nullable|image|mimes:jpeg,png,jpg,gif|max:2048'];
        }
        return $rules;
    }
}
