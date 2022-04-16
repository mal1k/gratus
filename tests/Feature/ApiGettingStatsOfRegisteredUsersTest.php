<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class ApiGettingStatsOfRegisteredUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_stats_of_registered_users_can_be_gotten_by_a_week()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/get-stats', [
            'timeType' => 'week', 
            'range' => [
                'begin' => 7, 
                'end' => 'today'
            ],
            'model' => 'users',
        ]);

        $response->assertStatus(200);
    }

    public function test_stats_of_registered_users_can_be_gotten_by_a_month()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/get-stats', [
            'timeType' => 'day', 
            'range' => [
                'begin' => 30, 
                'end' => 'today'
            ],
            'model' => 'users',
        ]);

        $response->assertStatus(200);
    }

    public function test_stats_of_registered_users_can_be_gotten_by_a_year()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/get-stats', [
            'timeType' => 'month', 
            'range' => [
                'begin' => 12, 
                'end' => 'today'
            ],
            'model' => 'users',
        ]);

        $response->assertStatus(200);
    }
}