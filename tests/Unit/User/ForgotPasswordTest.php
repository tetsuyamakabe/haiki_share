<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_利用者側パスワードリセットメールの送信処理()
    {
        // fakeメソッドの準備
        Notification::fake();
    
        // テスト用のユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // テストデータの作成
        $data = [
            'email' => 'user@example.com',
        ];

        // テスト用のリクエストを作成
        $response = $this->json('POST', '/api/user/password/email', $data);

        // レスポンスが正常であるか
        $response->assertStatus(200);
        // メールが送信されたか
        Notification::assertSentTo($user, ResetPassword::class);

        // レスポンスデータを取得
        $responseData = $response->json();
    
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
    
        // レスポンスデータの内容が正しいか
        $this->assertEquals('パスワードリセットリンクを送信しました。', $responseData['message']);
    }
}
