<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function test_利用者側ログイン処理()
    {
        // テストユーザーの作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // ログイン用のデータ
        $data = [
            'email' => 'user@example.com',
            'password' => 'password',
        ];

        // APIエンドポイントにPOSTリクエストを送信
        $response = $this->json('POST', '/api/user/login', $data);

        // レスポンスのステータスコードを検証
        $response->assertStatus(200);

        // レスポンスデータを取得
        $responseData = $response->json();

        // レスポンスデータが期待通りの構造か確認
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);

        // レスポンスデータの内容を検証
        $this->assertEquals('認証に成功しました', $responseData['message']);
        $this->assertEquals($user->id, $responseData['user_id']);
    }
}