<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Review;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_review_list()
    {
        $shop = Shop::factory()->create();
        $reviews = Review::factory()->count(5)->create([
            'shop_id' => $shop->id,
        ]);

        $response = $this->get('/review/' . $shop->id);
        foreach ($reviews as $review) {
            $response->assertSee($review->comment);
        }
    }

    public function test_sort_reviews_by_latest()
    {
        $shop = Shop::factory()->create();
        $reviews = Review::factory()->createMany([
            [
                'shop_id' => $shop->id,
                'evaluation' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 3,
                'created_at' => Carbon::now()->subDay(1),
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 1,
                'created_at' => Carbon::now()->subDay(2),
            ],
        ]);
        $response = $this->get('/review/sort/' . $shop->id . '?sort=latest')
        ->assertStatus(200);

        $dates = $response->viewData('reviews')
        ->pluck('created_at')
        ->map->timestamp->toArray();

        $sorted = $dates;
        rsort($sorted);
        $this->assertEquals($sorted, $dates);
    }

    public function test_sort_reviews_by_oldest()
    {
        $shop = Shop::factory()->create();
        $reviews = Review::factory()->createMany([
            [
                'shop_id' => $shop->id,
                'evaluation' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 3,
                'created_at' => Carbon::now()->subDay(1),
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 1,
                'created_at' => Carbon::now()->subDay(2),
            ],
        ]);
        $response = $this->get('/review/sort/' . $shop->id . '?sort=oldest')
        ->assertStatus(200);

        $dates = $response->viewData('reviews')
        ->pluck('created_at')
        ->map->timestamp->toArray();

        $sorted = $dates;
        sort($sorted);
        $this->assertEquals($sorted, $dates);
    }

    public function test_sort_reviews_by_highest_evaluation()
    {
        $shop = Shop::factory()->create();
        $reviews = Review::factory()->createMany([
            [
                'shop_id' => $shop->id,
                'evaluation' => 5,
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 3,
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 1,
            ],
        ]);
        $response = $this->get('/review/sort/' . $shop->id . '?sort=high')
        ->assertStatus(200);

        $evaluations = $response->viewData('reviews')
        ->pluck('evaluation')
        ->toArray();

        $this->assertEquals([5, 3, 1], $evaluations);
    }

    public function test_sort_reviews_by_lowest_evaluation()
    {
        $shop = Shop::factory()->create();
        $reviews = Review::factory()->createMany([
            [
                'shop_id' => $shop->id,
                'evaluation' => 5,
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 3,
            ],
            [
                'shop_id' => $shop->id,
                'evaluation' => 1,
            ],
        ]);
        $response = $this->get('/review/sort/' . $shop->id . '?sort=low')
        ->assertStatus(200);

        $evaluations = $response->viewData('reviews')
        ->pluck('evaluation')
        ->toArray();

        $this->assertEquals([1, 3, 5], $evaluations);
    }

    public function test_guest_cannot_add_review()
    {
        $shop = Shop::factory()->create();
        $response = $this->post('/review/' . $shop->id);
        $response->assertRedirect('/login');
    }

    public function test_user_can_add_review()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($user)->post('/review/' . $shop->id, [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'evaluation' => 4,
            'comment' => 'テストコメント',
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'evaluation' => 4,
            'comment' => 'テストコメント',
        ]);

        $this->get('/review/' . $shop->id)
        ->assertSee('テストコメント');
    }

    public function test_user_can_update_review()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $review = Review::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'evaluation' => 4,
            'comment' => 'テストコメント',
        ]);
        $this->actingAs($user)->get('/review/' . $shop->id)
        ->assertSee('テストコメント')
        ->assertSee('edit-icon');

        $response = $this->patch('/review/update', [
            'id' => $review->id,
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'evaluation' => 2,
            'comment' => 'テストコメントテストコメント',
        ]);
        $response->assertJson(['status' => 'success']);
        $this->actingAs($user)->get('/review/' . $shop->id)
        ->assertSee('テストコメントテストコメント');

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'evaluation' => 2,
            'comment' => 'テストコメントテストコメント',
        ]);
    }

    public function test_user_can_delete_review()
    {
        $user = User::factory()->create();
        $shop = SHop::factory()->create();
        $review = Review::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'evaluation' => 3,
            'comment' => 'テストコメント',
        ]);
        $this->actingAs($user)->get('/review/' . $shop->id)
        ->assertSee('テストコメント')
        ->assertSee('edit-icon');

        $response = $this->delete('/review/delete', [
            'id' => $review->id,
        ]);
        $response->assertRedirect('/review/' . $shop->id);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
        $this->actingAs($user)->get('/review/' . $shop->id)
        ->assertDontSee('テストコメント');
    }
}
