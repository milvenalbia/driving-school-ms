<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Instructor::class;

    public function definition()
    {
        return [
            'created_by' => User::factory(),
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->address,
            'emergency_phone_number' => $this->faker->phoneNumber,
            'emergency_fullname' => $this->faker->name,
            'student_assigned' => $this->faker->numberBetween(1, 1),
        ];
    }
    
}
