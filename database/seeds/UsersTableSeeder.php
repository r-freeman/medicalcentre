<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        // use UserFactory to create a user and attach admin role
        factory(User::class, 1)->create()->each(function ($user) {
            $user->roles()->attach(Role::where('name', 'admin')->first());
        });

        // use UserFactory to create 25 users and attach doctor role
        factory(User::class, 25)->create()->each(function ($user) {
            $user->roles()->attach(Role::where('name', 'doctor')->first(), [
                // set start_date to today on role_data pivot table
                'start_date' => new DateTime()
            ]);
        });

        // use UserFactory to create 50 user and attach patient role
        factory(User::class, 50)->create()->each(function ($user) {
            $insured = mt_rand(0, 1);

            $user->roles()->attach(Role::where('name', 'patient')->first(), [
                // set insured and policy_no attributes on role_data pivot table
                'insured' => $insured,
                'policy_no' => $insured ? Str::upper(Str::random(10)) : null
            ]);
        });
    }
}
