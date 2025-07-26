<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;

class SendReminderMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remainder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインドメールを送信する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $target = Carbon::today();
        $reservations = Reservation::with('user')
        ->whereDate('date', $target)
        ->get();

        foreach ($reservations as $reservation) {
            if ($reservation->user && $reservation->user->email) {
                Mail::to($reservation->user->email)
                ->send(new ReminderMail($reservation));
            }
        }
        $this->info('リマインドメールを送信しました（' . $reservations->count() . '件）');
    }
}
