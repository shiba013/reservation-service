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

    public function __construct(Reservation $reservation, string $testQrCode = null)
    {
        $this->reservation = $reservation;

        if ($testQrCode !== null) {
            $this->qrCode = $testQrCode;
            return;
        }

        $url = URL::signedRoute('qr.show', ['reservation' => $reservation->id]);
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
