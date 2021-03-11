<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['Digital', 'Poupança', 'Salário', 'Corrente', 'Empresarial']),
            'balance' => $this->faker->numberBetween(0, 100),
            'is_default' => false
        ];
    }

}
