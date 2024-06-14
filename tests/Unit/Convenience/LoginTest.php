<?php

namespace Tests\Unit\Convenience;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_コンビニ側ログイン処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create([
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'role' => 'convenience'
        ]);
        // テストデータの作成
        $data = [
            'email' => 'convenience@example.com',
            'password' => 'password',
        ];
        // テスト用のリクエストを送信
        $response = $this->json('POST', '/api/convenience/login', $data);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('認証に成功しました。', $responseData['message']);
        $this->assertEquals($user->id, $responseData['user_id']);
    }

    public function test_コンビニ側ログアウト処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create(['role' => 'convenience']);
        // テストユーザーのログイン
        Auth::login($user);
        // ログアウトのリクエストを送信
        $response = $this->post('/api/convenience/logout');
        // レスポンスが正常かどうかを確認
        $response->assertStatus(200);
        // レスポンスに正しい内容が含まれているか
        $response->assertJson(['message' => 'ログアウトしました。']);
        // ユーザーがログアウトできているか
        $this->assertFalse(Auth::check());
    }

    // 異常系テスト
    public function test_コンビニ側ユーザーが見つからない場合のログイン()
    {
        // テストデータの作成
        $data = [
            'email' => '12345@example.com', // 存在しないユーザーのメールアドレス
            'password' => '123456789', // 存在しないユーザーのパスワード
        ];
        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/login', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJson(['errors' => ['email' => ['メールアドレスかパスワードが間違っています。']]]);
    }

    public function test_利用者ユーザーのログイン処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'), // ハッシュ化
            'role' => 'user'
        ]);
        // テストデータの作成
        $data = [
            'email' => 'user@example.com', // 利用者ユーザーのメールアドレス
            'password' => 'password',
        ];
        // リクエストを送信
        $response = $this->json('POST', '/api/convenience/password/email', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJson(['errors' => ['email' => ['メールアドレスが無効です。']]]);
    }

    public function test_利用者側ログインバリデーションチェック()
    {
        // テストデータの作成
        $data = [
            'email' => 'aaaaa', // 不正な形式のメールアドレス
            'password' => 'pass', // パスワードが短すぎる
        ];
        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/login', $data);
        // バリデーションエラーが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}