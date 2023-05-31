<?php

namespace Database\Factories;

use App\Core\Types\LogLevelType;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserLogFactory extends Factory
{
    protected $model = UserLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = $this->user();

        return [
            'user_id' => $user::inRandomOrder()->get()->first()->id,
            'log_level' => 'INFO',
            'context' => 'Login',
            'ipv4' => $this->faker->ipv4()
        ];
    }

    public function user()
    {
        return $this->faker->randomElement([
            User::class
        ]);
    }
}
