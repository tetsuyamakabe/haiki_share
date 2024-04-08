<?php

namespace App\Http\Controllers\Accounts\Convenience;

use App\Models\User;
use App\Models\Convenience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Convenience\ProfileRequest;

class MyProfileController extends Controller
{
    // マイページ画面の表示
    public function showMyPage(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        return view('accounts.convenience.mypage', ['user' => $user]);
    }

    // プロフィール編集画面の表示
    public function showProfile(Request $request, $userId)
    {
        // dd($userId);
        $user = User::findOrFail($userId);
        // dd($user);
        $convenience = $user->convenience;
        // dd($convenience);
        $address = $convenience->address;
        return view('accounts.convenience.profile', ['user' => $user, 'convenience' => $convenience, 'address' => $address]);
    }

    // プロフィール編集・更新処理
    public function editProfile(ProfileRequest $request, $userId)
    {
        \Log::info('$userIdは、', [$userId]);
        \Log::info('リクエストは、:', $request->all());

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
    public function showWithdraw($userId)
    {
        $user = User::findOrFail($userId);
        return view('accounts.convenience.withdraw', ['user' => $user]);
    }

    // 退会処理の実行
    public function withdraw(Request $request, $userId)
    {
        $user = Auth::user();

        // コンビニ情報を取得
        if ($user->role === 'convenience') {
            $convenience = Convenience::where('user_id', $user->id)->first();
            if ($convenience) {
                $convenience->delete(); // 論理削除を実行
                $convenience->address()->delete(); // 住所情報を取得して論理削除
            }
        }
        $user->delete(); // ユーザー情報を論理削除
        Auth::logout(); // ログアウト
        return redirect('/'); // home画面に遷移
    }
}