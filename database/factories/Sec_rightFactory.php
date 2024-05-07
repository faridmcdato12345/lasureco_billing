<?php

namespace Database\Factories;

use App\Models\Sec_right;
use Illuminate\Database\Eloquent\Factories\Factory;

class Sec_rightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sec_right::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'right_name' => $this->faker->name,
            'right_description' => $this->faker->word
        ];
    }
}
