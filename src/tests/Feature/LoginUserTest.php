<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password0123',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => ''
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_information_discrepancy()
    {
        $user = User::factory()->create([
            'email' => 'yes@test.com',
            'password' => bcrypt('password0123'),
        ]);
        $response = $this->post('/login', [
            'email' => 'no@test.com',
            'password' => 'password9876',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function test_success_login()
    {
        $user = User::factory()->create([
            'email' => 'yes@test.com',
            'password' => bcrypt('password0123'),
        ]);
        $response = $this->post('/login', [
            'email' => 'yes@test.com',
            'password' => 'password0123',
        ]);
        $response->assertRedirect('/');
    }
}
