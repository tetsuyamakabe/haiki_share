<?php

namespace App\Http\Controllers\Auth\Convenience;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Convenience\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // パスワード変更メール送信処理
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            // DBからemailをキーにしてユーザーの存在を確認
            $user = User::where('email', $request->email)->first();
            // ユーザーが見つからない場合とroleが利用者ユーザーの場合はエラーを返す
            if (!$user || $user->role == 'user') {
                return response()->json(['errors' => ['email' => ['メールアドレスが無効です。']]], 422);
            }
            // パスワード変更メール送信実行
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            if ($response == Password::RESET_LINK_SENT) {
                // パスワードリセットリンクが送信された場合
                return response()->json(['message' => 'パスワードリセットメールを送信しました。'], 200);
            } else {
                // パスワードリセットリンクの送信に失敗した場合
                return response()->json(['errors' => ['email' => ['パスワードリセットメールの送信に失敗しました。']]], 422);
            }
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'パスワードリセットメールを送信できませんでした。'], 500);
        }
    }
}