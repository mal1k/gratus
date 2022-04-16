<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class ApiCreationOfReceiversTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_a_receiver_with_all_respective_relative_tables_successfully()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/receivers/create', [
            'receiver[email]' => 'email@example.com',
            'receiver[first_name]' => 'Test',
            'receiver[last_name]' => 'User',
            'receiver[password]' => 'password12',
            'receiver[status]' => 'Active',

            'bank_account[account_number]' => '4444444444444444',
            'bank_account[bank_code]' => '444444',

            'credit_card[expired_date]' => '24/11',
            'credit_card[number]' => '4444444444',
            'credit_card[secret_code]' => '4444',
            'credit_card[zip_code]' => '5677-333',

<<<<<<< HEAD
=======
            'receiver_data[is_kyc_passed]' => true,
>>>>>>> 2c04c23 (Init commit)
            'receiver_data[birth_date]' => '1950-12-31',
            'receiver_data[phone_number]' => '+1-900-500-50-50',
            'receiver_data[postal_code]' => '55555-666',
            'receiver_data[ssn]' => '4444',
            'receiver_data[address]' => 'Address',
            'receiver_data[address_2]' => 'Address 2',
            'receiver_data[state]' => 'State',
            'receiver_data[city]' => 'City',
            'receiver_data[country]' => 'Country',
        ]);

        $response->assertExactJson(['success' => 'Passed data were successfully saved']);
        $response->assertStatus(200);
    }

    public function test_create_a_receiver_without_a_credit_card_successfully()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/receivers/create', [
            'receiver[email]' => 'email@example.com',
            'receiver[first_name]' => 'Test',
            'receiver[last_name]' => 'User',
            'receiver[password]' => 'password12',
            'receiver[status]' => 'Active',

            'bank_account[account_number]' => '4444444444444444',
            'bank_account[bank_code]' => '444444',

            'credit_card[expired_date]' => '',
            'credit_card[number]' => '',
            'credit_card[secret_code]' => '',
            'credit_card[zip_code]' => '',

<<<<<<< HEAD
=======
            'receiver_data[is_kyc_passed]' => true,
>>>>>>> 2c04c23 (Init commit)
            'receiver_data[birth_date]' => '1950-12-31',
            'receiver_data[phone_number]' => '+1-900-500-50-50',
            'receiver_data[postal_code]' => '55555-666',
            'receiver_data[ssn]' => '4444',
            'receiver_data[address]' => 'Address',
            'receiver_data[address_2]' => 'Address 2',
            'receiver_data[state]' => 'State',
            'receiver_data[city]' => 'City',
            'receiver_data[country]' => 'Country',
        ]);

        $response->assertExactJson(['success' => 'Passed data were successfully saved']);
        $response->assertStatus(200);
    }

    public function test_create_a_receiver_without_a_bank_account_successfully()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/receivers/create', [
            'receiver[email]' => 'email@example.com',
            'receiver[first_name]' => 'Test',
            'receiver[last_name]' => 'User',
            'receiver[password]' => 'password12',
            'receiver[status]' => 'Active',

            'bank_account[account_number]' => '',
            'bank_account[bank_code]' => '',

            'credit_card[expired_date]' => '24/11',
            'credit_card[number]' => '4444444444',
            'credit_card[secret_code]' => '4444',
            'credit_card[zip_code]' => '5677-333',

<<<<<<< HEAD
=======
            'receiver_data[is_kyc_passed]' => true,
>>>>>>> 2c04c23 (Init commit)
            'receiver_data[birth_date]' => '1950-12-31',
            'receiver_data[phone_number]' => '+1-900-500-50-50',
            'receiver_data[postal_code]' => '55555-666',
            'receiver_data[ssn]' => '4444',
            'receiver_data[address]' => 'Address',
            'receiver_data[address_2]' => 'Address 2',
            'receiver_data[state]' => 'State',
            'receiver_data[city]' => 'City',
            'receiver_data[country]' => 'Country',
        ]);

        $response->assertExactJson(['success' => 'Passed data were successfully saved']);
        $response->assertStatus(200);
    }

    public function test_create_a_receiver_without_his_data_with_errors()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/receivers/create', [
            'receiver[email]' => 'email@example.com',
            'receiver[first_name]' => 'Test',
            'receiver[last_name]' => 'User',
            'receiver[password]' => 'password12',
            'receiver[status]' => 'Active',

            'bank_account[account_number]' => '4444444444444444',
            'bank_account[bank_code]' => '444444',

            'credit_card[expired_date]' => '24/11',
            'credit_card[number]' => '4444444444',
            'credit_card[secret_code]' => '4444',
            'credit_card[zip_code]' => '5677-333',

            'receiver_data[birth_date]' => '',
            'receiver_data[phone_number]' => '',
            'receiver_data[postal_code]' => '',
            'receiver_data[ssn]' => '',
            'receiver_data[address]' => '',
            'receiver_data[address_2]' => '',
            'receiver_data[state]' => '',
            'receiver_data[city]' => '',
            'receiver_data[country]' => '',
        ]);

        $response->assertJsonFragment(['message' => 'The given data was invalid.']);
    }
}
