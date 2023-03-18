<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tache>
 */
class TacheFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->locale('fr_FR');
        return [
            'nom'=>$this->faker->sentence(),
            'done'=>$this->faker->randomElement([true, false]),
            'description'=>$this->faker->paragraph(),
            'date_prevue'=>$this->faker->date()
        ];
    }
}
