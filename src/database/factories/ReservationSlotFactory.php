<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\ReservationSlot;

class ReservationSlotFactory extends Factory
{
    protected $model = ReservationSlot::class;

    public function definition()
    {
        $base = Carbon::now();

        return [
            'shop_id' => Shop::factory(),
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTIme(19, 0),
            'max_number' => 20,
            'max_group' => 6,
            'is_active' => 1,
        ];
    }
}
