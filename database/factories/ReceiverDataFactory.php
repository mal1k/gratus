<?php

namespace Database\Factories;

use App\Models\ReceiverData;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiverDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReceiverData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'receiver_id' => Receiver::factory(),
            'is_kyc_passed' => true,
            'phone_number' => $this->faker->e164PhoneNumber(),
            'ssn' => $this->faker->randomNumber(4, true),
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'address_2' => $this->faker->secondaryAddress(),
            'postal_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
        ];
    }
}
