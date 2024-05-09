<?php

namespace Tests\Unit\Convenience;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Convenience;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_コンビニ側ユーザー登録処理()
    {
        $data = [
            'name' => 'ローソン',
            'email' => 'convenience@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'convenience',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'town' => '新宿',
            'building' => '1-2-26',
            'branch_name' => '新宿一丁目店',
            'agreement' => 'true'
        ];

        // APIエンドポイントにPOSTリクエストを送信
        $response = $this->json('POST', '/api/convenience/register', $data);

        // レスポンスのステータスコードを検証
        $response->assertStatus(201);

        // レスポンスデータを取得
        $responseData = $response->json();

        // ユーザーが登録されたことを確認
        $this->assertArrayHasKey('user', $responseData);
        $this->assertArrayHasKey('convenience', $responseData);
        $this->assertArrayHasKey('address', $responseData);

        // ユーザー情報をデータベースから取得して検証
        $user = User::find($responseData['user']['id']);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertEquals($data['role'], $user->role);

        // コンビニ情報をデータベースから取得して検証
        $convenience = Convenience::find($responseData['convenience']['id']);
        $this->assertEquals($data['branch_name'], $convenience->branch_name);

        // 住所情報をデータベースから取得して検証
        $address = Address::find($responseData['address']['id']);
        $this->assertEquals($data['prefecture'], $address->prefecture);
        $this->assertEquals($data['city'], $address->city);
        $this->assertEquals($data['town'], $address->town);
        $this->assertEquals($data['building'], $address->building);
    }
}