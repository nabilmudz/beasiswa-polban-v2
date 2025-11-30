<?php

namespace Database\Factories;

use App\Models\Reviewer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reviewer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Menggunakan factory User
            'nip' => $this->faker->numerify('##########'),
            'role_id' => rand(1, 4), // Sesuaikan dengan role_id yang valid
        ];
    }
}
