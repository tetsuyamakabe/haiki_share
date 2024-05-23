<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_利用者ユーザー登録処理()
    {
        // テストデータの作成
        $data = [
            'name' => 'テストユーザー', // 名前
            'email' => 'user@example.com', // メールアドレス
            'password' => 'password', // パスワード
            'password_confirmation' => 'password', // パスワード（再入力）
            'role' => 'user', // 利用者ユーザーかどうか
            'agreement' => 'true' // 利用規約の同意
        ];

        // テスト用のリクエストを送信
        $response = $this->json('POST', '/api/user/register', $data);

        // レスポンスが正常であるか
        $response->assertStatus(201);

        // Userモデルからユーザー情報を取得
        $user = User::first();

        // ユーザーが存在するか
        $this->assertNotNull($user);

        // ユーザー情報が正しいか（名前、メールアドレス、パスワード、role）
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertEquals($data['role'], $user->role);
    }

    // 異常系テスト
    public function test_利用者側ユーザー登録バリデーションチェック()
    {
        // テストデータの作成
        $data = [
            'name' => '', // 必須項目なので空にする
            'email' => 'aaaaa', // 不正な形式のメールアドレス
            'password' => 'pass', // パスワードが短すぎる
            'password_confirmation' => 'password', // パスワード確認が一致しない
            'role' => 'convenience', // コンビニユーザーになっている
            'agreement' => 'false' // 利用規約の同意が得られていない
        ];

        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/user/register', $data);

        // バリデーションエラーが返されるか
        $response->assertStatus(422);

        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'role', 'agreement']);
    }
}