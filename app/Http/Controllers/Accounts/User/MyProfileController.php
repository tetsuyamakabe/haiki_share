<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    // マイページ画面の表示
    public function showMyPage(Request $request, $userId)
    {
        $user = User::find($userId);
        return view('accounts.user.mypage', ['user' => $user]);
    }

    // プロフィール編集画面の表示
    public function showProfile(Request $request, $userId)
    {
        $user = User::find($userId);
        return view('accounts.user.profile', ['user' => $user]);
    }

    // プロフィール編集・更新処理
    public function editProfile(Request $request, $userId)
    {
        // ユーザー情報を更新（パスワード、自己紹介、顔写真を追加する必要あり）
        $user = User::find($userId);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return view('accounts.user.mypage', ['user' => $user]);
    }

    // 退会画面の表示
    public function showWithdraw(Request $request)
    {
        return view('accounts.user.withdraw');
    }
}