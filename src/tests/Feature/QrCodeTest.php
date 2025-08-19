<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;
use App\Models\Shop;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationSlot;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_code_expected_url()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
        ]);
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reservation_slot_id' => $reservationSlot->id,
        ]);
        $url = URL::signedRoute('reserveList', ['shop_id' => $shop->id]);
        $qrCode = QrCode::format('svg')->size(200)->generate($url);

        $this->assertStringContainsString($url, $qrCode);
    }
}
