<?php

namespace Database\Factories;

use App\Models\CreditCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CreditCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $number = (int) $this->faker->randomNumber(9, true) .''. $this->faker->randomNumber(9, true);
        
        return [
            'cardable_id' => '',
            'cardable_type' => '',
            'number' => $number,
            'expired_date' => now()->addYears(5)->format('y/m'),
            'secret_code' => $this->faker->randomNumber(4, true),
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
