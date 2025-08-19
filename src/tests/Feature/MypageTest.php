<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationSlot;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_mypage()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mypage');
        $response->assertSee($user->name);
    }

    public function test_can_see_reserve_list()
    {
        $base = Carbon::now();
        $user = User::factory()->create();
        $shop = SHop::factory()->create();
        $reservationSlot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
        ]);
        Reservation::factory()->createMany([
            [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'reservation_slot_id' => $reservationSlot->id,
                'date' => $base->copy()->format('Y-m-d'),
            ],
            [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'reservation_slot_id' => $reservationSlot->id,
                'date' => $base->copy()->addDay(1)->format('Y-m-d'),
            ],
            [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'reservation_slot_id' => $reservationSlot->id,
                'date' => $base->copy()->addDay(2)->format('Y-m-d'),
            ],
        ]);

        $response = $this->actingAs($user)->get('/mypage')
        ->assertSee($base->copy()->format('Y-m-d'))
        ->assertSee($base->copy()->addDay(1)->format('Y-m-d'))
        ->assertSee($base->copy()->addDay(2)->format('Y-m-d'));
    }

    public function test_can_see_favorites_list()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post('/favorite/' . $shop->id);
        $response->assertJson([
            'status' => 'added',
        ]);
        $this->actingAs($user)->get('/mypage')
        ->assertSee($shop->name)
        ->assertSee('favorite-icon on')
        ->assertDontSee('favorite-icon off');
    }

    public function test_guest_cannot_access()
    {
        $response = $this->get('/mypage');
        $response->assertRedirect('/login');
    }

    public function test_change_my_reservation()
    {
        $this->withoutExceptionHandling();
        $base = Carbon::now()->addDay();
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $slot1 = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $slot2 = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->addDay(1)->format('Y-m-d'),
            'reserve_start' => $base->copy()->addDay(1)->setTime(18, 0),
            'reserve_end' => $base->copy()->addDay(1)->setTime(20, 0),
        ]);
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reservation_slot_id' => $slot1->id,
            'date' => $slot1->date->format('Y-m-d'),
            'time' => $slot1->reserve_start->format('H:i:s'),
            'number' => 3,
        ]);

        $response = $this->actingAs($user)->patch('/reserve/update/' . $reservation->id, [
            'action' => '確定',
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reservation_slot_id' => $slot2->id,
            'date' => $slot2->date->format('Y-m-d'),
            'time' => $slot2->reserve_start->format('H:i:s'),
            'number' => 5,
        ]);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reservation_slot_id' => $slot2->id,
            'date' => $slot2->date->format('Y-m-d'),
            'time' => $slot2->reserve_start->format('H:i:s'),
            'number' => 5,
        ]);
        $this->actingAs($user)->get('/mypage')
        ->assertSee($reservation->name)
        ->assertSee('18:00')
        ->assertSee(5);
    }

    public function test_delete_my_reservation()
    {
        $base = Carbon::now();
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $slot = ReservationSlot::factory()->create([
            'shop_id' => $shop->id,
            'date' => $base->copy()->format('Y-m-d'),
            'reserve_start' => $base->copy()->setTime(17, 0),
            'reserve_end' => $base->copy()->setTime(19, 0),
        ]);
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date' => $slot->date->format('Y-m-d'),
            'time' => $slot->reserve_start->format('H:i:s'),
            'number' => 5,
        ]);

        $response = $this->actingAs($user)->delete('/reserve/delete/' . $reservation->id, [
            'reservation_id' => $reservation->id,
        ]);
        $response->assertRedirect('/mypage');
        $this->actingAs($user)->get('/mypage')
        ->assertDontSee($reservation->name)
        ->assertDontSee('17:00')
        ->assertDontSee(5 . '人');
    }

    public function test_delete_favorite_shop()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post('/favorite/' . $shop->id);
        $response->assertJson([
            'status' => 'added',
        ]);
        $this->actingAs($user)->get('/mypage')
        ->assertSee($shop->name)
        ->assertSee('favorite-icon on');

        $response = $this->actingAs($user)->post('/favorite/' . $shop->id);
        $response->assertJson([
            'status' => 'removed',
        ]);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }
}
