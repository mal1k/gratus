<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\{
    Receiver,
    ReceiverData,
    User
};

class ApiUpdationOfReceiversTest extends TestCase
{
    use RefreshDatabase;

    private $receiver;

    protected function setUp() : void
    {
        parent::setUp();

        $this->receiver = Receiver::factory()
            ->has(ReceiverData::factory()->count(1))
            ->hasCreditCard(1, function(array $attributes, Receiver $receiver) {
                $attributes['cardable_id'] = $receiver->id;
                $attributes['cardable_type'] = Receiver::class;
                return $attributes;
            })
            ->hasBankAccount(1, function(array $attributes, Receiver $receiver) {
                $attributes['accountable_id'] = $receiver->id;
                $attributes['accountable_type'] = Receiver::class;
                return $attributes;
            })
            ->create();
    }

    public function test_retrieve_the_concrete_receiver_from_database()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/receivers/show', [
            'item' => $this->receiver->id,
        ]);

        $this->assertEquals($this->receiver->id, $response['id']);
    }

    public function test_update_the_concrete_receiver_successfully()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/manager/receivers/update', $this->changeAndPrepareTheReceiver());

<<<<<<< HEAD
        $response->dump();

=======
>>>>>>> 2c04c23 (Init commit)
        $response->assertStatus(200);
    }

    private function changeAndPrepareTheReceiver() : array
    {
        $return = [];
        $receiver = $this->receiver->getAttributes();
        $receiverData = $this->receiver->receiverData->getAttributes();
        $creditCard = $this->receiver->creditCard->getAttributes();
        $bankAccount = $this->receiver->bankAccount->getAttributes();

        foreach ($receiver as $key => $value) {
            $nameKey = 'receiver['. $key .']';
            if ($key === 'status') {
                switch($value) {
                    case Receiver::STATUS_ACTIVE:
                        $value = 'Active';
                        break;
                    case Receiver::STATUS_BLOCKED:
                        $value = 'Blocked';
                        break;
                    case Receiver::STATUS_NOT_VERIFIED:
                        $value = 'Not verified';
                        break;
                }
            }
            $return[$nameKey] = $value;
        }

        foreach ($receiverData as $key => $value) {
            $nameKey = 'receiver_data['. $key .']';
            $return[$nameKey] = $value;
        }

        foreach ($creditCard as $key => $value) {
            $nameKey = 'credit_card['. $key .']';
            $return[$nameKey] = $value;
        }

        foreach ($bankAccount as $key => $value) {
            $nameKey = 'bank_account['. $key .']';
            $return[$nameKey] = $value;
        }

        return $return;
    }
}
