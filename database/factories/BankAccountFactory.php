<?php

namespace Database\Factories;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bankCode = $this->faker->randomNumber(5, true) .''. $this->faker->randomNumber(5, true);
        $accountNumber = $this->faker->randomNumber(5, true) .''.
                    $this->faker->randomNumber(5, true) .''.
                    $this->faker->randomNumber(5, true) .''.
                    $this->faker->randomNumber(5, true);

        return [
            'accountable_id' => '',
            'accountable_type' => '',
            'bank_code' => $bankCode,
            'account_number' => $accountNumber,
        ];
    }
}
