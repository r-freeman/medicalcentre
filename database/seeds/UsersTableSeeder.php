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
        $faker = Faker\Factory::create();

        $roles = array(
            'admin'  => Role::where('name', 'admin')->first(),
            'doctor' => Role::where('name', 'doctor')->first(),
            'patient'=> Role::where('name', 'patient')->first()
        );

        // create a user
        $user = new User();
        $user->name = $faker->name;
        $user->address = $faker->streetAddress;
        $user->phone = $faker->phoneNumber;
        $user->email = $faker->safeEmail;
        $user->password = bcrypt('secret');
        $user->save();

        // attach admin role to user
        $user->roles()->attach($roles['admin']);

        // create a user
        $user = new User();
        $user->name = $faker->name;
        $user->address = $faker->streetAddress;
        $user->phone = $faker->phoneNumber;
        $user->email = $faker->safeEmail;
        $user->password = bcrypt('secret');
        $user->save();

        // attach doctor role and pass attributes into role_data pivot table
        $user->roles()->attach($roles['doctor'], [
            'start_date' => new DateTime()
        ]);

        // create user
        $user = new User();
        $user->name = $faker->name;
        $user->address = $faker->streetAddress;
        $user->phone = $faker->phoneNumber;
        $user->email = $faker->safeEmail;
        $user->password = bcrypt('secret');
        $user->save();

        // attach patient role and pass attributes into role_data pivot table
        $user->roles()->attach($roles['patient'], [
            'insured' => 0
        ]);
    }
}
