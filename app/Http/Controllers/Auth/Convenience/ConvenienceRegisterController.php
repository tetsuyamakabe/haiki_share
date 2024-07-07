<?php

namespace App\Http\Controllers\Auth\Convenience;

use App\Models\User;
use App\Models\Address;
use App\Models\Convenience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Convenience\RegisterRequest;
use App\Notifications\Convenience\RegisteredNotification;

class ConvenienceRegisterController extends Controller
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

    // ユーザー登録
    public function create(RegisterRequest $request)
    {
        try {
            // バリデーション済みデータの取得
            $validated = $request->validated();
            // ユーザー情報を「users」テーブルに保存
            $user = User::create([
                'name' => $validated['convenience_name'], // コンビニ名
                'email' => $validated['email'], // メールアドレス
                'password' => Hash::make($validated['password']), // パスワード
                'role' => $validated['role'], // role
            ]);
            // コンビニ情報を「convenience_stores」テーブルに保存
            if ($validated['role'] === 'convenience') {
                // 住所情報を保存し、そのIDを取得
                $address = Address::create([
                    'prefecture' => $validated['prefecture'], // 住所（都道府県）
                    'city' => $validated['city'], // 住所（市区町村）
                    'town' => $validated['town'], // 住所（地名・番地）
                ]);
                if (isset($validated['building'])) { // 住所（建物名・部屋番号）
                    $address->building = $validated['building'];
                    $address->save();
                }
                $addressId = $address->id;
                // コンビニ情報を保存
                $convenience = Convenience::create([
                    'user_id' => $user->id, // ユーザーID
                    'branch_name' => $validated['branch_name'], // 支店名
                    'address_id' => $addressId, // 住所ID
                ]);
                // ConvenienceモデルとAddressモデルの関連付け
                $convenience->address()->associate($address);
                $convenience->save();
                // ユーザー登録完了メールの送信
                Notification::send($user, new RegisteredNotification($user));
                return response()->json(['user' => $user, 'convenience' => $convenience, 'address' => $address], 201);
            }
        } catch (\Exception $e) {
            \Log::error('例外エラー: ' . $e->getMessage());
            return response()->json(['message' => 'ユーザー登録できませんでした。'], 500);
        }
    }
}