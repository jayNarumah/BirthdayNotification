<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Profile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_type_id' => 2,
            'profile_id' => Profile::inRandomOrder()->first(),
            // 'group_id' => 2,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
