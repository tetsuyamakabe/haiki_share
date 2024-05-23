<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\User\PasswordResetNotification;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_利用者側パスワードリセットメールの送信処理()
    {
        // fakeメソッドの準備
        Notification::fake();
    
        // テスト用のユーザーを作成
        $user = factory(User::class)->create();

        // テストデータの作成
        $data = [
            'email' => 'user@example.com',
        ];

        // テスト用のリクエストを送信
        $response = $this->json('POST', '/api/user/password/email', $data);

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
    public function test_利用者側ユーザーが見つからない場合のパスワードリセットメールの送信処理()
    {
        // テストデータの作成
        $data = [
            'email' => '12345@example.com', // 存在しないユーザーのメールアドレス
        ];

        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/user/password/email', $data);

        // エラーレスポンスが返されるか
        $response->assertStatus(404);

        // エラーメッセージが正しいか
        $response->assertJson(['message' => 'ユーザーが見つかりません']);
    }

    public function test_コンビニユーザーのパスワードリセットメールの送信処理()
    {
        // テスト用のユーザー（コンビニユーザー）を作成
        $user = User::create([
            'name' => 'コンビニユーザー',
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'role' => 'convenience',
        ]);

        // テストデータの作成
        $data = [
            'email' => 'convenience@example.com', // コンビニユーザーのメールアドレス
        ];

        // リクエストを送信
        $response = $this->json('POST', '/api/user/password/email', $data);

        // エラーレスポンスが返されるか
        $response->assertStatus(422);

        // エラーメッセージが正しいか
        $response->assertJson(['errors' => ['email' => ['このメールアドレスは利用者側のメールアドレスではありません。']]]);
    }

    public function test_利用者側パスワードリセットメール送信バリデーションチェック()
    {
        // テストデータの作成
        $data = [
            'email' => 'aaaaa', // 不正な形式のメールアドレス
        ];

        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/user/password/email', $data);

        // バリデーションエラーが返されるか
        $response->assertStatus(422);

        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['email']);
    }
}