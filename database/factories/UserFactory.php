<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'nama_depan' => $this->faker->firstName,
            'nama_belakang' => $this->faker->lastName,
            'jenis_kelamin' => $this->faker->randomElement(['Pria', 'Wanita']),
            'foto' => 'path/to/foto.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

}
