<?php

namespace App\Http\Controllers\Accounts\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ProfileRequest;

class MyPageController extends Controller
{
    // プロフィール情報の取得処理
    public function getProfile(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        return response()->json(['user' => $user]);
    }

    // プロフィール編集・更新処理
    public function editProfile(ProfileRequest $request, $userId)
    {
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());

        try {
            // ユーザー情報を取得
            $user = User::findOrFail($userId);
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
            return response()->json(['message' => 'プロフィール編集に成功しました', 'user' => $user]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'プロフィール編集に失敗しました'], 500);
        }
    }

    // 退会処理の実行
    public function withdraw(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        \Log::info('$userは、', [$user]);
        if ($user) {
            $user->delete(); // 論理削除を実行
            Auth::logout(); // 自動ログアウト
            return response()->json(['message' => 'ユーザーが退会しました', 'user' => $user]);
        } else {
            return response()->json(['message' => 'ユーザーが見つかりません'], 404);
        }
    }
}