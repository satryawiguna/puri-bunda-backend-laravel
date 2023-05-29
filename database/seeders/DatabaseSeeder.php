<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ini_set('memory_limit','512M');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('user_logs')->truncate();
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call(UserSeeder::class);
        $this->call(UserLogSeeder::class);

    }
}
