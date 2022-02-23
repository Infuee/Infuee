<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'first_name' => "Super",
            'last_name' => "Admin",
            'email' => 'admin@infuee.com',
            'image' => 'default.jpg', 
            'designation' => 'Super Admin',
            'phone' => '44(76)34254578', 
            'address' => 'Melbourne',
            'password' => Hash::make('Admin@123'),
        ]);
    }
}
