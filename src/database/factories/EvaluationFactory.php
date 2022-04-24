<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Evaluation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'skill_id' => Skill::factory(),
            'value' => $this->faker->randomFloat(2, 0.5, 5),
        ];
    }
}
