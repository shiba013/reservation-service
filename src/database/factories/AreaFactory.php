<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Area;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'area' => '東京都',
        ];
    }
}
