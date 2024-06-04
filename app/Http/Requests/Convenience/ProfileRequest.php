<?php

namespace App\Http\Requests\Convenience;

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
            // コンビニ名のバリデーションルール（必須、string型、最大文字数255文字以下）
            'convenience_name' => 'required|string|max:255',
            // 支店名のバリデーションルール（必須、string型、最大文字数255文字以下）
            'branch_name' => 'required', 'string', 'max:255',
            // 都道府県のバリデーションルール（必須、string型、最大文字数255文字以下）
            'prefecture' => 'required', 'string', 'max:255',
            // 市区町村のバリデーションルール（必須、string型、最大文字数255文字以下）
            'city' => 'required', 'string', 'max:255',
            // 地名・番地のバリデーションルール（必須、string型、最大文字数255文字以下）
            'town' => 'required', 'string', 'max:255',
            // 建物名・部屋番号のバリデーションルール（NULL許容、string型、最大文字数255文字以下）
            'building' => 'nullable', 'string', 'max:255',
            // パスワードのバリデーションルール（NULL許容、string型、最小文字数8文字以上、パスワード再入力と同じ値か、半角数字・英字大文字・小文字・記号を許可）
            'password' => 'nullable|string|min:8|confirmed|regex:/^[a-zA-Z0-9!@#$%^&*]+$/',
            // 自己紹介文のバリデーションルール（NULL許容、string型、最大文字数50文字）
            'introduction' => 'nullable|string|max:50',
        ];

        // メールアドレスが更新された場合のみバリデーションルールを適用
        if ($this->has('email')) {
            // メールアドレスのバリデーションルール（NULL許容、string型、メール形式、最大文字数255文字以下、ユニーク制約）
            $rules['email'] = 'nullable|string|email|max:255|unique:users,email,' . auth()->id();
        }

        // 顔写真がアップロードされた場合のみバリデーションルールを適用
        if ($this->hasFile('icon')) {
            // 顔写真のバリデーションルール（NULL許容、画像ファイル、拡張子、最大画像ファイルサイズ2MB以下）
            $rules['icon'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }
}