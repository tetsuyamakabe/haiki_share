<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Address;
use App\Models\Convenience;
use Illuminate\Database\Seeder;

class ConvenienceStoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // コンビニユーザーを作成
        $user = User::where('email', 'convenience@example.com')->first();
        // 住所情報を取得
        $address = Address::first();
        // テスト用のコンビニ情報の作成
        Convenience::create([
            'user_id' => $user->id,
            'branch_name' => '東京日本橋店',
            'address_id' => $address->id,
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}