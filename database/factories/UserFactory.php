<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $email = $this->faker->bothify('????????-#########') .'@'. $this->faker->bothify('????????-#########');

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            // 'email' => $email,
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$Z3vS.mp2xcHfRM8vbm3hTO6Wgv1FVKEtal9IqvvzEAAggreyBCJyG', // password12
            'remember_token' => Str::random(10),
            'status' => User::STATUS_PENDING,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
