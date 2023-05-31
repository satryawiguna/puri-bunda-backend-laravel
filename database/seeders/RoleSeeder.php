<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['title' => 'SUPER ADMIN', 'slug' => 'super-admin', 'created_by' => 'system'],
            ['title' => 'ADMIN', 'slug' => 'admin', 'created_by' => 'system'],
            ['title' => 'RECEPTIONIST', 'slug'=> 'receptionist', 'created_by' => 'system'],
            ['title' => 'CASHIER', 'slug'=> 'cashier', 'created_by' => 'system']
        ]);
    }
}
