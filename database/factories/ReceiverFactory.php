<?php

namespace Database\Factories;

use App\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReceiverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receiver::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            // 'email' => $email,
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$Z3vS.mp2xcHfRM8vbm3hTO6Wgv1FVKEtal9IqvvzEAAggreyBCJyG', // password12
            'remember_token' => Str::random(10),
            'status' => Receiver::STATUS_ACTIVE,
        ];
    }
}
