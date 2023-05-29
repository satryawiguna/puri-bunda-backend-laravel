<?php

namespace Database\Factories;

use App\Core\Types\UserType;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $contactable = $this->contactable();

        return [
            'contactable_id' => $contactable::factory(),
            'contactable_type' => $contactable,
            'type' => 'EMPLOYEE',
            'nick_name' => $this->faker->firstName(),
            'full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'created_by' => 'system'
        ];
    }

    public function contactable()
    {
        return $this->faker->randomElement([
            User::class
        ]);
    }
}
