<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::updateOrcreate(['name' => 'admin']);
        $userRole = Role::updateOrcreate(['name' => 'user']);


        $user = User::updateOrcreate(['email'=>'admin@example.com'],['first_name'=>'Super',
                                                					 'last_name'=>'Admin',
                                                                     'email_verified_at' => now(),
                                                					 'password'=>Hash::make('password')]);

        $user->assignRole($superAdminRole);

        // For create dummy users
        // $users = factory(User::class, 3)->create();

        // $users->each(function($user) use ($userRole){
        //     $user->assignRole($userRole);
        // });
    }
}
