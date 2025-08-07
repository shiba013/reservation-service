<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'shop_id' => Shop::factory(),
            'evaluation' => $this->faker->numberBetween(1, 5),
            'comment' => Str::limit($this->faker->sentence(), 200),
        ];
    }
}
