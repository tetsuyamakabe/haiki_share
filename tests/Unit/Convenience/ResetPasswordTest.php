<?php

namespace Tests\Unit\Convenience;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    // 正常系テスト
    public function test_コンビニ側パスワード変更処理()
    {
        // テスト用のコンビニユーザーを作成
        $user = factory(User::class)->create([
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'role' => 'convenience'
        ]);
        // テストデータの作成
        $data = [
            'email' => 'convenience@example.com', // ユーザーのメールアドレス
            'oldPassword' => 'password', // 現在のパスワード
            'newPassword' => '12345678', // 新しいパスワード
            'password_confirmation' => '12345678', // 新しいパスワードの確認
        ];
        // テスト用のリクエストを送信
        $response = $this->json('POST', '/api/convenience/password/reset', $data);
        // レスポンスが正常であるか
        $response->assertStatus(200);
        // レスポンスデータを取得
        $responseData = $response->json();
        // レスポンスデータに必要な情報が含まれているか
        $this->assertArrayHasKey('message', $responseData);
        // レスポンスデータの内容が正しいか
        $this->assertEquals('パスワードが変更されました', $responseData['message']);
    }

    // 異常系テスト
    public function test_コンビニ側ユーザーが見つからない場合のパスワード変更処理()
    {
        // テストデータの作成
        $data = [
            'email' => '12345@example.com', // 存在しないユーザーのメールアドレス
            'oldPassword' => 'aaaaaaaa', // 現在のパスワード
            'newPassword' => 'abcdefgh', // 新しいパスワード
            'password_confirmation' => 'abcdefgh', // 新しいパスワードの確認
        ];
        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/password/reset', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(404);
        // エラーメッセージが正しいか
        $response->assertJson(['message' => 'ユーザーが見つかりません']);
    }

    public function test_利用者ユーザーのパスワード変更処理()
    {
        // テスト用の利用者ユーザーを作成
        $user = factory(User::class)->create([
            'name' => '利用者ユーザー',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
        // テストデータの作成
        $data = [
            'email' => 'user@example.com', // 存在しないユーザーのメールアドレス
            'oldPassword' => 'aaaaaaaa', // 現在のパスワード
            'newPassword' => 'abcdefgh', // 新しいパスワード
            'password_confirmation' => 'abcdefgh', // 新しいパスワードの確認
        ];
        // リクエストを送信
        $response = $this->json('POST', '/api/convenience/password/reset', $data);
        // エラーレスポンスが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJson(['errors' => ['email' => ['このメールアドレスはコンビニ側のメールアドレスではありません。']]]);
    }

    public function test_コンビニ側パスワード変更バリデーションチェック()
    {
        // テストデータの作成
        $data = [
            'oldPassword' => 'aaaaa', // 古いパスワードが違う
            'newPassword' => 'pass', // 新しいパスワードが短すぎる
            'password_confirmation' => 'password', // パスワード確認が一致しない
        ];
        // 不正なリクエストを送信
        $response = $this->json('POST', '/api/convenience/password/reset', $data);
        // バリデーションエラーが返されるか
        $response->assertStatus(422);
        // エラーメッセージが正しいか
        $response->assertJsonValidationErrors(['oldPassword', 'newPassword']);
    }
}