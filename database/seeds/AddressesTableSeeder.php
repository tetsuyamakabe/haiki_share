<?php

use Carbon\Carbon;
use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テスト用住所情報の作成
        Address::create([
            'prefecture' => '東京都',
            'city' => '中央区',
            'town' => '日本橋',
            'building' => '１丁目２−１９',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}