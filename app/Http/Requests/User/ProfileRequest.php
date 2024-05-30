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
        $rules = [
            // 名前のバリデーションルール（必須、string型、最大文字数255文字以下）
            'name' => 'required|string|max:255',
            // パスワードのバリデーションルール（NULL許容、string型、最小文字数8文字以上、パスワード再入力と同じ値か）
            'password' => 'nullable|string|min:8|confirmed',
            // 自己紹介文のバリデーションルール（NULL許容、string型、最大文字数50文字）
            'introduction' => 'nullable|string|max:50',
        ];

        // メールアドレスが更新された場合のみバリデーションルールを適用
        if ($this->has('email')) {
            // メールアドレスのバリデーションルール（NULL許容、string型、メール形式、最大文字数255文字以下、ユニーク制約）
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . auth()->id();
        }

        // 顔写真がアップロードされた場合のみバリデーションルールを適用
        if ($this->hasFile('icon')) {
            // 顔写真のバリデーションルール（NULL許容、画像ファイル、拡張子、最大画像ファイルサイズ2MB以下）
            $rules['icon'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }
}
