<?php

namespace App\Http\Controllers\Accounts\Convenience;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyProfileController extends Controller
{
    // マイページ画面の表示
    public function showMyPage(Request $request)
    {
        return view('accounts.convenience.mypage');
    }

    // プロフィール編集画面の表示
    public function showProfile(Request $request)
    {
        return view('accounts.convenience.profile');
    }

    // 退会画面の表示
    public function showWithdraw(Request $request)
    {
        return view('accounts.convenience.withdraw');
    }
}
