<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Crypt;

class MobileApiRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_be_registered_with_correct_data_by_api()
    {
        $response = $this->post('/api/mobile/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password12',
            'password_confirmation' => 'password12',
            'device_name' => Crypt::encryptString('127.0.0.1-iPhone'),
        ]);

        $response->assertStatus(201);
    }

    public function test_the_500_error_is_occured_when_the_attribute_isnt_encrypted_on_regs()
    {
        $response = $this->post('/api/mobile/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password12',
            'password_confirmation' => 'password12',
            'device_name' => 'iPhone',
        ]);

        $response->assertStatus(500);
        $response->assertExactJson(['errors' => 'Hey mate! There is a server error is occured']);
    }
}
