<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviews = [
            [
                'user_id' => 1,
                'shop_id' => 1,
                'evaluation' => 5,
                'comment' => 'とてもおいしかった',
            ],
            [
                'user_id' => 1,
                'shop_id' => 4,
                'evaluation' => 5,
                'comment' => '楽しかった',
            ],
            [
                'user_id' => 2,
                'shop_id' => 1,
                'evaluation' => 4,
                'comment' => 'おいしかった',
            ],
            [
                'user_id' => 2,
                'shop_id' => 4,
                'evaluation' => 4,
                'comment' => 'おしゃれな雰囲気だった',
            ],
            [
                'user_id' => 3,
                'shop_id' => 1,
                'evaluation' => 3,
            ],
            [
                'user_id' => 3,
                'shop_id' => 4,
                'evaluation' => 3,
            ],
        ];
        foreach ($reviews as $review) {
            DB::table('reviews')->insert([
                'user_id' => $review['user_id'],
                'shop_id' => $review['shop_id'],
                'evaluation' => $review['evaluation'],
                'comment' => $review['comment'] ?? null,
            ]);
        }
    }
}
