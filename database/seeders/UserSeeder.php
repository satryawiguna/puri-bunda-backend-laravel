<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = (new Role())->where('slug', '=', 'ADMINISTRATOR')->first();

        $user = User::create([
            'role_id' => $role->id,
            'username' => 'admin',
            'email' => 'admin@puri-bunda.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_by' => 'system'
        ]);

        $user->contact()->create([
            'nick_name' => 'Satrya',
            'full_name' => 'Satrya Wiguna',
            'created_by' => 'system'
        ]);

        Contact::factory()->count(10)->create();
    }
}
