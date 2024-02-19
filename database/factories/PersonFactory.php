<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'user_id' => function () {
                return User::factory();
            },
            'family_id' => function () {
                return Family::factory();
            },
            'avatar' => $this->faker->imageUrl(),
        ];
    }
}
