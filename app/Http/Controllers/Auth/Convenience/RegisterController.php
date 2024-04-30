<?php

namespace App\Http\Controllers\Auth\Convenience;

use App\Models\User;
use App\Models\Address;
use App\Models\Convenience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Convenience\RegisterRequest;

class RegisterController extends Controller
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
        // バリデーション済みデータの取得
        $validated = $request->validated();

        // ユーザー情報を「users」テーブルに保存
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // コンビニ情報を「convenience_stores」テーブルに保存
        if ($validated['role'] === 'convenience') {
            // 住所情報を保存し、そのIDを取得
            $address = Address::create([
                'prefecture' => $validated['prefecture'],
                'city' => $validated['city'],
                'town' => $validated['town'],
                'building' => $validated['building'],
            ]);
            $addressId = $address->id;

            // コンビニ情報を保存
            $convenience = Convenience::create([
                'user_id' => $user->id,
                'branch_name' => $validated['branch_name'],
                'address_id' => $addressId,
            ]);

            // ConvenienceモデルとAddressモデルの関連付け
            $convenience->address()->associate($address);
            $convenience->save();

            return response()->json(['user' => $user, 'convenience' => $convenience, 'address' => $address], 201);
        }
    }
}