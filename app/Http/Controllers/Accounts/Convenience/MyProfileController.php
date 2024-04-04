<?php

namespace App\Http\Controllers\Accounts\Convenience;

use App\Models\User;
use App\Models\Convenience;
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
        return view('accounts.convenience.mypage', ['user' => $user]);
    }

    // プロフィール編集画面の表示
    public function showProfile(Request $request, $userId)
    {
        $user = User::find($userId);
        $convenience = $user->convenience;
        $address = $convenience->address;
        return view('accounts.convenience.profile', ['user' => $user, 'convenience' => $convenience, 'address' => $address]);
    }

    // プロフィール編集・更新処理
    public function editProfile(Request $request, $userId)
    {
        // ユーザー情報を取得
        $user = User::find($userId);
        
        // ユーザー情報を更新
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->icon = $request->input('icon');
        $user->introduction = $request->input('introduction');
        $user->save();

        // コンビニ情報を取得
        $convenience = Convenience::where('user_id', $userId)->first();

        // コンビニ情報を更新
        if ($convenience) {
            $convenience->branch_name = $request->input('branch_name');
            
            // 住所情報の更新
            $address = $convenience->address;
            if ($address) {
                $address->prefecture = $request->input('prefecture');
                $address->city = $request->input('city');
                $address->town = $request->input('town');
                $address->building = $request->input('building');
                $address->save();
            }

            $convenience->save();
        }

        // マイページを表示
        return view('accounts.convenience.mypage', ['user' => $user]);
    }

    // 退会画面の表示
    public function showWithdraw(Request $request)
    {
        return view('accounts.convenience.withdraw');
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