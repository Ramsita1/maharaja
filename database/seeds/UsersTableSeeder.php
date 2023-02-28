<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'role' => 'Admin',
            'user_nicename' => 'admin',
            'name' => 'admin',
            'user_login' => 'admin@gmail.com',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'user_registered' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
