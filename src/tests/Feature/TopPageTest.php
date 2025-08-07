<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;

class TopPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_shop_list()
    {
        $shops = Shop::factory()->count(5)->create();
        $response = $this->get('/')->assertStatus(200);
        foreach ($shops as $shop) {
            $response->assertSee($shop->name);
        }
    }

    public function test_can_be_search_area()
    {
        $area = Area::factory()->create([
            'area' => '東京都',
        ]);
        Shop::factory()->count(5)->create([
            'area_id' => $area->id,
        ]);
        $response = $this->get('/search?area=' . $area->id);
        $response->assertSee('東京都');
    }

    public function test_can_be_search_genre()
    {
        $genre = Genre::factory()->create([
            'genre' => '居酒屋',
        ]);
        Shop::factory()->count(5)->create([
            'genre_id' => $genre->id,
        ]);
        $response = $this->get('/search?genre=' . $genre->id);
        $response->assertSee('居酒屋');
    }

    public function test_can_be_search_keyword()
    {
        Shop::factory()->create([
            'name' => 'ダミー店',
        ]);
        $response = $this->get('/search?keyword=ダミー');
        $response->assertSee('ダミー店');
    }
}
