<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;


    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/auth/login');
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }


    public function test_guest_cannot_update_profile(): void
    {
        $response = $this->patchJson('api/profile', [
            'name'    => '`Dima',
            'phone'   => '+79990001122',
            'address' => 'New Address',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name'    => 'Old Name',
            'phone'   => '+70000000000',
            'address' => 'Old Address',
        ]);

        $response = $this->actingAs($user)->patchJson('api/profile', [
            'name'    => 'New Name',
            'phone'   => '+79990001122',
            'address' => 'New Address',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'New Name');

        $this->assertDatabaseHas('users', [
            'userId'  => $user->userId,
            'name'    => 'New Name',
            'phone'   => '+79990001122',
            'address' => 'New Address',
        ]);
    }

    public function test_updating_profile_with_missing_fields_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson('api/profile', []);

        $response->assertStatus(422);
    }

    public function test_updating_profile_does_not_affect_other_users(): void
    {
        $user       = User::factory()->create(['name' => 'User 1']);
        $otherUser  = User::factory()->create(['name' => 'User 2']);

        $this->actingAs($user)->patchJson('/profile', [
            'name'    => 'Changed Name',
            'phone'   => '+79990001122',
            'address' => 'Address',
        ]);

        $this->assertDatabaseHas('users', [
            'userId' => $otherUser->userId,
            'name'   => 'User 2',
        ]);
    }
}
