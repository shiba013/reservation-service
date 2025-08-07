<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationSlot;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_date_is_required()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $base = Carbon::now();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $response = $this->actingAs($user)
        ->post('/detail/' . $shop->id, [
            'date' => '',
            'time' => '17:00',
            'number' => 2,
        ]);
        $response->assertSessionHasErrors([
            'date' => '日付を選択してください',
        ]);
    }

    public function test_time_is_required()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $base = Carbon::now();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $response = $this->actingAs($user)
        ->post('/detail/' . $shop->id, [
            'date' => $base->copy()->format('Y-m-d'),
            'time' => '',
            'number' => 2,
        ]);
        $response->assertSessionHasErrors([
            'time' => '時間を選択してください',
        ]);
    }

    public function test_number_is_required()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $base = Carbon::now();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $response = $this->actingAs($user)
        ->post('/detail/' . $shop->id, [
            'date' => $base->copy()->format('Y-m-d'),
            'time' => '17:00',
            'number' => '',
        ]);
        $response->assertSessionHasErrors([
            'number' => '人数を選択してください',
        ]);
    }

    public function test_guest_is_cannot_reserve()
    {
        $shop = Shop::factory()->create();
        $base = Carbon::now();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $response = $this->post('/detail/' . $shop->id, [
            'date' => $base->copy()->format('Y-m-d'),
            'time' => '17:00',
            'number' => 2,
        ]);
        $response->assertRedirect('/login');
    }

    public function test_success_reserve_by_user()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $base = Carbon::now();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $response = $this->actingAs($user)
        ->post('/detail/' . $shop->id, [
            'date' => $base->copy()->format('Y-m-d'),
            'time' => '17:00',
            'number' => 2,
        ]);
        $response->assertRedirect('/done');

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'time' => '17:00:00',
            'number' => 2,
        ]);
    }
}
