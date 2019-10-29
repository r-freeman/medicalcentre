<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed the roles table with an admin role
        $role_admin = new Role();
        $role_admin->name = 'admin';
        $role_admin->description = 'An administrator';
        $role_admin->save();

        // seed the roles table with a doctor role
        $role_doctor = new Role();
        $role_doctor->name = 'doctor';
        $role_doctor->description = 'A doctor';
        $role_doctor->save();

        // seed the roles table with a patient role
        $role_patient = new Role();
        $role_patient->name = 'patient';
        $role_patient->description = 'A patient';
        $role_patient->save();
    }
}
