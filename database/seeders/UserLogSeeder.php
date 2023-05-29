<?php

namespace Database\Seeders;

use App\Models\UserLog;
use Illuminate\Database\Seeder;

class UserLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserLog::factory()->count(200)->create();
    }
}
