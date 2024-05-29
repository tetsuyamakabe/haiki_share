<?php

namespace App\Http\Controllers\Auth\Convenience;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Requests\Convenience\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // パスワード変更処理
    public function reset(ResetPasswordRequest $request)
    {
        try {
            // ユーザーを特定するための情報を取得
            $email = $request->email; // ユーザーのメールアドレス
            $password = $request->newPassword; // 新しいパスワード
            // DBからemailをキーにしてユーザー情報を取得
            $user = User::where('email', $email)->first();
            // ユーザーが見つからない場合
            if (!$user) {
                return response()->json(['message' => 'ユーザーが見つかりません'], 404);
            }
            // roleがuserの場合は422エラーを返す
            if ($user->role == 'user') {
                return response()->json(['errors' => ['email' => ['このメールアドレスはコンビニ側のメールアドレスではありません。']]], 422);
            }
            // パスワードを更新
            $user->password = Hash::make($password);
            $user->save();
            return response()->json(['message' => 'パスワードが変更されました']);
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'パスワードが変更されませんでした。'], 500);
        }
    }
}