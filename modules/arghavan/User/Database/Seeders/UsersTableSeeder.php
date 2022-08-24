<?php

namespace arghavan\User\Database\Seeders;

use arghavan\User\Models\User;
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

        foreach (User::$defultUsers as $user){

            User::firstOrCreate(
                ['email' => $user['email']]
                ,[
                "email" => $user['email'],
                "name" => $user['name'],
                "password" => bcrypt($user['password'])
            ])->assignRole($user['role'])->markEmailAsVerified();
        }



//        Role::findOrCreate('manage role_permissions')->givePermissionTo(['manage role_permissions']);
//        Role::findOrCreate('manage categories')->givePermissionTo(['manage categories']);

    }
}
