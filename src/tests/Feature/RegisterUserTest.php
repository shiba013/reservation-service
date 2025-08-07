<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'name' => 'ユーザーネームを入力してください',
        ]);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => '',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '',
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_password_is_under7_characters()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'pass',
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    public function test_success()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password0123',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'test@test.com',
        ]);
        $user = User::where('email', 'test@test.com')->first();
        $this->actingAs($user)
        ->get('/thanks')
        ->assertRedirect('/email/verify');
    }
}
