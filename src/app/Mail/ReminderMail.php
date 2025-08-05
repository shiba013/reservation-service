<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;


class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $reservation;
    public $qrCode;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;

        $url = URL::signedRoute('reserveList', ['shop' =>$reservation->shop->id]);

        $this->qrCode = QrCode::size(200)->generate($url);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('(リマインド) ご予約日は本日です')
        ->view('mails.reminder');
    }
}
