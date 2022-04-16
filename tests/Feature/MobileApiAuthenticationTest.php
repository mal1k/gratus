<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Crypt;

class MobileApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_be_logged_in_with_the_credentials_by_api()
    {
        $user = User::factory()->create();

        Sanctum::actingAs( $user, ['*'] );

        $response = $this->post('/api/mobile/login', [
            'email' => $user->email,
            'password' => 'password12',
            'device_name' => Crypt::encryptString('iPhone'),
        ]);

        $response->assertStatus(200);
    }

    public function test_users_can_not_authenticate_with_invalid_password_by_api()
    {
        $user = User::factory()->create();

        Sanctum::actingAs( $user, ['*'] );

        $response = $this->post('/api/mobile/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => Crypt::encryptString('iPhone'),
        ]);

        $response->assertStatus(401);
        $response->assertExactJson(['errors' => 'The provided credentials are incorrect.']);
    }

    public function test_the_500_error_is_occured_when_the_device_name_is_not_encrypted()
    {
        $user = User::factory()->create();

        Sanctum::actingAs( $user, ['*'] );

        $response = $this->post('/api/mobile/login', [
            'email' => $user->email,
            'password' => 'password12',
            'device_name' => 'iPhone',
        ]);

        $response->assertStatus(500);
        $response->assertExactJson(['errors' => 'Hey mate! There is a server error is occured']);
    }
}
