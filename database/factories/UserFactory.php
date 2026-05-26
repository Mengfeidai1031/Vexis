<?php

namespace Database\Factories;

use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'empresa_id' => Empresa::query()->inRandomOrder()->value('id') ?? Empresa::factory(),
            'centro_id' => Centro::query()->inRandomOrder()->value('id'),
            'departamento_id' => Departamento::query()->inRandomOrder()->value('id'),
            'telefono' => fake()->numerify('6########'),
            'extension' => fake()->optional()->numerify('###'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
