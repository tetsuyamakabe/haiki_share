<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Address;
use App\Models\Convenience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/home';

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
        if ($data['role'] === 'user') {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'string'],
            ]);
        } elseif ($data['role'] === 'convenience') {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'branch_name' => ['required', 'string', 'max:255'],
                'prefecture' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'town' => ['required', 'string', 'max:255'],
                'building' => ['string', 'max:255'],
                'role' => ['required', 'string'],
            ]);
        }
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // 共通のユーザー情報を「users」テーブルに保存
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // コンビニの場合は追加情報を「convenience_stores」テーブルに保存
        if ($data['role'] === 'convenience') {
            // 住所情報を保存し、そのIDを取得
            $address = Address::create([
                'prefecture' => $data['prefecture'],
                'city' => $data['city'],
                'town' => $data['town'],
                'building' => $data['building'],
            ]);
            $addressId = $address->id;

            // コンビニ情報を保存
            $convenience = Convenience::create([
                'user_id' => $user->id,
                'branch_name' => $data['branch_name'],
                'address_id' => $addressId,
            ]);

            // ConvenienceとAddressの関連付け
            $convenience->address()->associate($address);
            $convenience->save();

            return $user;
        }
    }

    // ユーザー登録画面の表示
    public function show(Request $request)
    {
        return view('auth.register');
    }
}