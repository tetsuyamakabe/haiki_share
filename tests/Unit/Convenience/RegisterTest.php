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

    // 正常系テスト
    public function test_コンビニ側ユーザー登録処理()
    {
        // テストデータの作成
        $data = [
            'convenience_name' => 'ローソン', // コンビニ名
            'email' => 'convenience@example.com', // メールアドレス
            'password' => 'password', // パスワード
            'password_confirmation' => 'password', // パスワード（再入力）
            'role' => 'convenience', // コンビニユーザーかどうか
            'prefecture' => '東京都', // 住所（都道府県）
            'city' => '新宿区', // 住所（市区町村）
            'town' => '新宿', // 住所（地名・番地）
            'building' => '1-2-26', // 住所（建物名・部屋番号）
            'branch_name' => '新宿一丁目店', // 支店名
            'agreement' => 'true' // 利用規約の同意
        ];

        // テスト用のリクエストを作成
        $response = $this->json('POST', '/api/convenience/register', $data);

        // レスポンスが正常であるか
        $response->assertStatus(201);

        // レスポンスデータを取得
        $responseData = $response->json();

        // レスポンスの中にそれぞれのユーザー情報が含まれているか
        $this->assertArrayHasKey('user', $responseData);
        $this->assertArrayHasKey('convenience', $responseData);
        $this->assertArrayHasKey('address', $responseData);

        // ユーザー情報が正しいか（ユーザーID、コンビニ名、メールアドレス、パスワード、role）
        $user = User::find($responseData['user']['id']);
        $this->assertEquals($data['convenience_name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertEquals($data['role'], $user->role);

        // コンビニ情報が正しいか（コンビニID、支店名）
        $convenience = Convenience::find($responseData['convenience']['id']);
        $this->assertEquals($data['branch_name'], $convenience->branch_name);

        // 住所情報が正しいか（住所ID、都道府県、市区町村、地名・番地、建物名・部屋番号）
        $address = Address::find($responseData['address']['id']);
        $this->assertEquals($data['prefecture'], $address->prefecture);
        $this->assertEquals($data['city'], $address->city);
        $this->assertEquals($data['town'], $address->town);
        $this->assertEquals($data['building'], $address->building);
    }

    // 異常系テスト
    public function test_コンビニ側ユーザー登録バリデーションチェック()
    {
        // テストデータの作成
        $data = [
            'convenience_name' => '', // 必須項目なので空にする
            'email' => 'aaaaa', // 不正な形式のメールアドレス
            'password' => 'pass', // パスワードが短すぎる
            'password_confirmation' => 'password', // パスワード確認が一致しない
            'role' => 'user', // 利用者ユーザーになっている
            'prefecture' => '', // 必須項目なので空にする
            'city' => '', // 必須項目なので空にする
            'town' => '', // 必須項目なので空にする
            'branch_name' => '', // 必須項目なので空にする
            'agreement' => 'false' // 利用規約の同意が得られていない
        ];

        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/register', $data);

        // バリデーションエラーが返されるか
        $response->assertStatus(422);

        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['convenience_name', 'email', 'password', 'role', 'prefecture', 'city', 'town', 'branch_name', 'agreement']);
    }
}