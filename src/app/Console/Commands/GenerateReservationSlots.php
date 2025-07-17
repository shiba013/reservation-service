<?php

namespace App\Console\Commands;

use App\Services\ReservationSlotService;
use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\ReservationSlot;
use Illuminate\Support\Carbon;

class GenerateReservationSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:reservation-slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '2年後までの予約枠を生成';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ReservationSlotService $slotService)
    {
        $this->slotService = $slotService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();
        $startDate = $today;
        $endDate = $startDate->copy()->addYears(2)->endOfMonth();

        $this->info('2年分の予約枠の作成を開始します');

        $shops = Shop::all();
        foreach ($shops as $shop) {
            $this->slotService->generateSlots($shop, $startDate, $endDate);
            $this->line("{$shop->name}の予約枠を作成しました");
        }
    }
}
