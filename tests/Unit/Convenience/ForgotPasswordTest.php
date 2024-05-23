<?php

namespace Tests\Unit\Convenience;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\Convenience\PasswordResetNotification;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_コンビニ側パスワードリセットメールの送信処理()
    {
        // fakeメソッドの準備
        Notification::fake();
    
        // テスト用のユーザーを作成
        $user = User::create([
            'name' => 'ローソン',
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'role' => 'convenience',
        ]);

        // テストデータの作成
        $data = [
            'email' => 'convenience@example.com',
        ];

        // テスト用のリクエストを送信
        $response = $this->json('POST', '/api/convenience/password/email', $data);

        // レスポンスが正常であるか
        $response->assertStatus(200);

        // メールが送信されたか
        Notification::assertSentTo($user, PasswordResetNotification::class);

        // レスポンスデータを取得
        $responseData = $response->json();
    
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
    
        // レスポンスデータの内容が正しいか
        $this->assertEquals('パスワードリセットリンクを送信しました。', $responseData['message']);
    }

    // 異常系テスト
    public function test_コンビニ側ユーザーが見つからない場合のパスワードリセットメールの送信処理()
    {
        // テストデータの作成
        $data = [
            'email' => '12345@example.com', // 存在しないユーザーのメールアドレス
        ];

        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/password/email', $data);

        // エラーレスポンスが返されるか
        $response->assertStatus(404);

        // エラーメッセージが正しいか
        $response->assertJson(['message' => 'ユーザーが見つかりません']);
    }

    public function test_利用者ユーザーのパスワードリセットメールの送信処理()
    {
        // テスト用のユーザー（利用者ユーザー）を作成
        $user = User::create([
            'name' => '利用者ユーザー',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // テストデータの作成
        $data = [
            'email' => 'user@example.com', // 利用者ユーザーのメールアドレス
        ];

        // リクエストを送信
        $response = $this->json('POST', '/api/convenience/password/email', $data);

        // エラーレスポンスが返されるか
        $response->assertStatus(422);

        // エラーメッセージが正しいか
        $response->assertJson(['errors' => ['email' => ['このメールアドレスはコンビニ側のメールアドレスではありません。']]]);
    }

    public function test_コンビニ側パスワードリセットメール送信バリデーションチェック()
    {
        // テストデータの作成
        $data = [
            'email' => 'aaaaa', // 不正な形式のメールアドレス
        ];

        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/password/email', $data);

        // バリデーションエラーが返されるか
        $response->assertStatus(422);

        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['email']);
    }
}