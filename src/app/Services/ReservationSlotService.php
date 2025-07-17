<?php

namespace App\Services;

use App\Models\ReservationSlot;
use App\Models\Shop;
use Carbon\Carbon;

class ReservationSlotService
{
    public function generateSlots(Shop $shop, Carbon $startDate, Carbon $endDate): void
    {
        $intervalMinutes = 30;
        $maxGroup = 6;
        $maxNumber = 20;
        $length = 120;

        \Log::info("generateSlots開始 shop_id={$shop->id} startDate={$startDate->toDateString()} endDate={$endDate->toDateString()}");

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $exists = ReservationSlot::where('shop_id', $shop->id)
            ->where('date', $date->format('Y-m-d'))
            ->exists();

            if ($exists) {
                \Log::info("既に予約枠あり shop_id={$shop->id} date={$date->format('Y-m-d')} 生成スキップ");
                continue;
            }

            $startTime = $shop->start_time->format('H:i');
            $endTime = $shop->end_time->format('H:i');

            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $startTime);
            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $endTime);

            while ($start < $end) {
                $reserveEnd = $start->copy()->addMinutes($length);
                if ($reserveEnd > $end) {
                    break;
                }

                ReservationSlot::create([
                    'shop_id' => $shop->id,
                    'date' => $date->format('Y-m-d'),
                    'reserve_start' => $start->format('H:i'),
                    'reserve_end' => $start->copy()->addMinutes($length)->format('H:i'),
                    'max_number' => $maxNumber,
                    'max_group' => $maxGroup,
                    'is_active' => true,
                ]);
                $start->addMinutes($intervalMinutes);
            }
            \Log::info("{$shop->name}:{$date->format('Y-m-d')}の予約枠を生成しました");
        }
    }
}
