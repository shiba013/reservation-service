<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\User;
use App\Models\Shop;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "2年分の予約枠の作成を開始します \n";
        Artisan::call('generate:reservation-slots');

        $reservations = [];
        $users = User::pluck('id')->toArray();

        echo "1週間分のダミーデータの作成を開始します \n";
        for ($day = 0; $day < 7; $day++) {
            $date = Carbon::today()->addDays($day);

            $slotIds = ReservationSlot::where('date', $date)->pluck('id')->toArray();

            for ($i = 0; $i < 8; $i ++) {
                $slotId = $slotIds[array_rand($slotIds)];
                $slot = ReservationSlot::find($slotId);

                $reservations[] = [
                    'user_id' => $users[array_rand($users)],
                    'shop_id' => 4,
                    'reservation_slot_id' => $slotId,
                    'date' => $slot->date,
                    'time' => $slot->reserve_start,
                    'number' => rand(1, 6),
                    'is_paid' => false,
                ];
            }
        }
        DB::table('reservations')->insert($reservations);
    }
}
