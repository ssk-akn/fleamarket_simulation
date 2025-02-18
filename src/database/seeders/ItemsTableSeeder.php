<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clockId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 1,
            'name' => '腕時計',
            'price' => 15000,
            'image' => 'images/clock.jpg',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $clockId, 'category_id' => 5],
            ['item_id' => $clockId, 'category_id' => 12],
        ]);
        $diskId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 2,
            'name' => 'HDD',
            'price' => 5000,
            'image' => 'images/disk.jpg',
            'description' => '高速で信頼性の高いハードディスク',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $diskId, 'category_id' => 2],
        ]);
        $onionId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 3,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'image' => 'images/onions.jpg',
            'description' => '新鮮な玉ねぎ3束のセット',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $onionId, 'category_id' => 10],
        ]);
        $shoesId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 4,
            'name' => '革靴',
            'price' => 4000,
            'image' => 'images/shoes.jpg',
            'description' => 'クラシックなデザインの革靴',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $shoesId, 'category_id' => 1],
            ['item_id' => $shoesId, 'category_id' => 5],
        ]);
        $pcId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 1,
            'name' => 'ノートPC',
            'price' => 45000,
            'image' => 'images/pc.jpg',
            'description' => '高性能なノートパソコン',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $pcId, 'category_id' => 1],
        ]);
        $micId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 2,
            'name' => 'マイク',
            'price' => 8000,
            'image' => 'images/mic.jpg',
            'description' => '高音質のレコーディング用マイク',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $micId, 'category_id' => 2],
        ]);
        $bagId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 3,
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'image' => 'images/bag.jpg',
            'description' => 'おしゃれなショルダーバッグ',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $bagId, 'category_id' => 1],
            ['item_id' => $bagId, 'category_id' => 4],
        ]);
        $tumblerId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 4,
            'name' => 'タンブラー',
            'price' => 500,
            'image' => 'images/tumbler.jpg',
            'description' => '使いやすいタンブラー',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $tumblerId, 'category_id' => 10],
        ]);
        $millId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 1,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'image' => 'images/mill.jpg',
            'description' => '手動のコーヒーミル',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $millId, 'category_id' => 3],
            ['item_id' => $millId, 'category_id' => 10],
        ]);
        $cosmeticId = DB::table('items')->insertGetId([
            'user_id' => rand(1, 5),
            'condition_id' => 2,
            'name' => 'メイクセット',
            'price' => 2500,
            'image' => 'images/cosmetic.jpg',
            'description' => '便利なメイクアップセット',
        ]);
        DB::table('item_category')->insert([
            ['item_id' => $cosmeticId, 'category_id' => 6],
        ]);
    }
}
