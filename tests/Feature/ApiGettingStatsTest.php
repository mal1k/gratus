<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class ApiGettingStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_stats_can_be_gotten_by_a_week()
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
}