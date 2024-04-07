<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    public function editProfile(Request $request, $userId)
    {
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());
        $user = User::find($userId);
        \Log::info('$userは、', [$user]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // パスワードの入力がある場合のみ更新する
        $password = $request->input('password');
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }

        // ファイルがアップロードされているか確認
        if ($request->hasFile('icon')) {
            $profileImage = $request->file('icon');
            $extension = $profileImage->getClientOriginalExtension(); // ファイルの拡張子を取得
            $fileName = sha1($profileImage->getClientOriginalName()) . '.' . $extension; // SHA-1ハッシュでファイル名を決定
            $profileImagePath = $profileImage->storeAs('public/icons', $fileName); // ファイルを保存
            $user->icon = $profileImagePath; // ファイルパスを保存
        }

        $user->introduction = $request->input('introduction');
        $user->save();

        return view('accounts.user.mypage', ['user' => $user]);
    }

    // 退会画面の表示
    public function showWithdraw(Request $request)
    {
        return view('accounts.user.withdraw');
    }

    // 退会処理の実行
    public function withdraw()
    {
        $user = Auth::user();
        $user->is_deleted = true; // 論理削除の実行
        $user->save();

        Auth::logout(); // 自動ログアウト

        return redirect()->route('home')->with('success', '退会処理が完了しました。');
    }
}