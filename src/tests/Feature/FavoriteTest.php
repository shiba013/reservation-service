<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_favorite()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $this->actingAs($user)->post('/favorite/' . $shop->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function test_favorite_icon_change_status()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->get('/')->assertSee('favorite-icon off');

        $response = $this->actingAs($user)->post('/favorite/' . $shop->id);
        $response->assertJson([
            'status' => 'added',
        ]);
        $this->actingAs($user)->get('/')->assertSee('favorite-icon on');
    }

    public function test_guest_cannot_add_favorite()
    {
        $shop = Shop::factory()->create();
        $response = $this->post('/favorite/' . $shop->id);
        $response->assertRedirect('/login');
    }
}
