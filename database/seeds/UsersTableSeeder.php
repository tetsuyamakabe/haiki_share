<?php

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テスト用利用者ユーザーの作成
        User::create([
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'icon' => 'default.png',
            'introduction' => '',
            'role' => 'user',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // テスト用コンビニユーザーの作成
        User::create([
            'name' => 'ローソン',
            'email' => 'convenience@example.com',
            'password' => bcrypt('password'),
            'icon' => 'default.png',
            'introduction' => '',
            'role' => 'convenience',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}