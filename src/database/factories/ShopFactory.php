<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\User;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition()
    {
        $base = Carbon::now();

        return [
            'user_id' => User::factory(),
            'area_id' => Area::factory(),
            'genre_id' => Genre::factory(),
            'name' => Str::limit($this->faker->word(), 50),
            'image' => 'storage/images/demo.png',
            'overview' => Str::limit($this->faker->sentence(), 500),
            'start_time' => $base->copy()->setTime(17, 0),
            'end_time' => $base->copy()->setTime(23, 0),
        ];
    }
}
