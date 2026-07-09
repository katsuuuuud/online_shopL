<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_page_opens(): void
    {
        $response = $this->get('/auth/login');

        $response->assertStatus(200);
    }

    public function test_register_page_opens(): void
    {
        $response = $this->get('/auth/register');

        $response->assertStatus(200);
    }

    public function test_user_can_register(): void {
        $response = $this->post('/auth/register', [
            'name'     => 'Jane Doe',
            'email'    => 'name@example.com',
            'phone'    => '+77777777777',
            'address'  => 'Abay 110',
            'password' => 'password123456',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'name@example.com',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    public function test_user_cannot_register_with_duplicate_email(): void {
        User::factory()->create(['email' => 'email@example.com']);
        $response = $this->post('/auth/register', [
            'name'    => 'Test User',
            'email'   => 'email@example.com',
            'phone'   => '+79990001122',
            'address' => 'Адрес',
            'password' => 'password123',
        ]);
        $response->assertStatus(500);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_fails_validation_without_required_fields(): void
    {
        $response = $this->post('/auth/register', [
            'email' => 'incomplete@example.com',
        ]);
        $response->assertSessionHasErrors(['name', 'phone', 'address', 'password']);
        $this->assertGuest();
    }

    public function test_api_register_returns_created_user_as_json(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'    => 'Api User',
            'email'   => 'api@example.com',
            'phone'   => '+79990001122',
            'address' => 'Адрес',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.email', 'api@example.com');

        $this->assertDatabaseHas('users', ['email' => 'api@example.com']);
    }

    public function test_api_register_with_duplicate_email_returns_422_json_error(): void
    {
        User::factory()->create(['email' => 'dup@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'name'    => 'Кто-то',
            'email'   => 'dup@example.com',
            'phone'   => '+79990001122',
            'address' => 'Адрес',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/auth/login', [
            'email'    => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'login2@example.com',
            'password' => Hash::make('correct-password'),
        ]);
        $response = $this->post('/auth/login', [
            'email'    => 'login2@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(500);
        $this->assertGuest();
    }

    public function test_login_validation_requires_email_and_password(): void
    {
        $response = $this->post('/auth/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_api_login_returns_user_json_on_success(): void
    {
        User::factory()->create([
            'email'    => 'apilogin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'apilogin@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.email', 'apilogin@example.com');
    }

    public function test_api_login_returns_error_on_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'apilogin2@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'apilogin2@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['error']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/auth/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_guest_cannot_logout(): void
    {
        $response = $this->post('/auth/logout');
        $response->assertRedirect('/auth/login');
    }
}

