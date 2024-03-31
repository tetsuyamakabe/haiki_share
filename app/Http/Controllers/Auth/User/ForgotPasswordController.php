<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\User\ForgotPasswordRequest;
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

    // パスワード変更画面（メールアドレス入力画面）の表示
    public function show(Request $request)
    {
        return view('auth.user.passwords.email');
    }

    // パスワード変更メール送信処理
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $this->validateEmail($request);

        // ユーザーの存在を確認し、存在しない場合はエラーメッセージを返す
        $user = User::where('email', $request->email)->first();
        if (!$user || $user->role == 'convenience') {
            return response()->json(['errors' => ['email' => ['このメールアドレスは利用者側のメールアドレスではありません。']]], 422);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}