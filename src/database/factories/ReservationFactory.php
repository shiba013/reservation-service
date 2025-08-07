<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationSlot;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $base = Carbon::now();

        return [
            'user_id' => User::factory(),
            'shop_id' => Shop::factory(),
            'reservation_slot_id' => ReservationSlot::factory(),
            'date' => $base->copy()->format('Y-m-d'),
            'time' => $base->copy()->setTime(18, 0),
            'number' => $this->faker->numberBetWeen(1, 10),
            'is_paid' => 0,
        ];
    }
}
