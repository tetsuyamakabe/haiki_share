<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\RegisterRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\User\RegisteredNotification;

class UserRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    // ユーザー登録処理
    public function create(RegisterRequest $request)
    {
        // バリデーション済みデータの取得
        $validated = $request->validated();
        // ユーザー情報を「users」テーブルに保存
        $user = User::create([
            'name' => $validated['name'], // 名前
            'email' => $validated['email'], // メールアドレス
            'password' => Hash::make($validated['password']), // パスワード
            'role' => $validated['role'], // role
        ]);
        // ユーザー登録完了メールの送信
        Notification::send($user, new RegisteredNotification($user));
        return response()->json($user, 201);
    }
}