<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ProfileRequest;

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
        \Log::info('showProfileメソッドの$userIdは、', [$userId]);
        $user = User::find($userId);
        \Log::info('showProfileメソッドの$userは、', [$user]);
        return view('accounts.user.profile', ['user' => $user]);
    }

    // プロフィール編集・更新処理
    public function editProfile(ProfileRequest $request, $userId)
    {
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());

        // ユーザー情報を取得
        $user = User::find($userId);
        \Log::info('$userは、', [$user]);

        // ユーザー情報を更新
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // パスワードの入力がある場合のみ更新する
        $password = $request->input('password');
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }

        $user->introduction = $request->input('introduction');

        // ファイルがアップロードされているか確認
        if ($request->hasFile('icon')) {
            $iconImage = $request->file('icon');
            $extension = $iconImage->getClientOriginalExtension(); // ファイルの拡張子を取得
            $fileName = sha1($iconImage->getClientOriginalName()) . '.' . $extension; // SHA-1ハッシュでファイル名を決定
            $iconImagePath = $iconImage->storeAs('public/icons', $fileName); // ファイルを保存
            $user->icon = $fileName; // ファイルパスを保存
        }

        $user->save();

        return view('accounts.user.mypage', ['user' => $user]);
    }

    // 退会画面の表示
    public function showWithdraw(Request $request, $userId)
    {
        $user = User::find($userId);
        return view('accounts.user.withdraw');
    }

    // 退会処理の実行
    public function withdraw(Request $request, $userId)
    {
        $user = Auth::user();
        $user->is_deleted = true; // 論理削除の実行
        $user->save();

        Auth::logout(); // 自動ログアウト

        return redirect()->route('home')->with('success', '退会処理が完了しました。');
    }
}