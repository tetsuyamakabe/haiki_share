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

    public function test_利用者側ユーザー登録処理()
    {
        $data = [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
            'agreement' => 'true'
        ];

        // テスト用のリクエストを作成
        $response = $this->json('POST', '/api/user/register', $data);

        // レスポンスが正常であることを確認
        $response->assertStatus(201);

        // データベースから新しいユーザーを取得
        $user = User::where('email', $data['email'])->first();

        // ユーザーが存在することを確認
        $this->assertNotNull($user);

        // ユーザーの各属性が正しいことを確認
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertEquals($data['role'], $user->role);
    }
}