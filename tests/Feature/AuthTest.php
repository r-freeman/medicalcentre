<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Asserts if a user needs to be logged in to see the dashboard
     *
     * @test
     */
    public function user_needs_to_be_logged_in_to_see_dashboard()
    {
        $response = $this->get('/home')->assertRedirect('/login');
    }

    /**
     * Asserts if a user with admin role can access the admin dashboard
     *
     * @test
     */
    public function user_with_admin_role_can_access_admin_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());

        $this->actingAs($user);
        $response = $this->get('/admin/home')->assertOk();
    }

    /**
     * Asserts if a user with doctor role can access the doctor dashboard
     *
     * @test
     */
    public function user_with_doctor_role_can_access_doctor_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'doctor')->first());

        $this->actingAs($user);
        $response = $this->get('/doctor/home')->assertOk();
    }

    /**
     * Asserts if a user with patient role can access the patient dashboard
     *
     * @test
     */
    public function user_with_patient_role_can_access_patient_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'patient')->first());

        $this->actingAs($user);
        $response = $this->get('/patient/home')->assertOk();
    }

    /**
     * Asserts if a user with admin role trying to access doctor dashboard is redirected home
     *
     * @test
     */
    public function user_with_admin_role_can_access_doctor_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());

        $this->actingAs($user);
        $response = $this->get('/doctor/home')->assertRedirect('/home');
    }

    /**
     * Asserts if a user with admin role trying to access patient dashboard is redirected home
     *
     * @test
     */
    public function user_with_admin_role_can_access_patient_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());

        $this->actingAs($user);
        $response = $this->get('/patient/home')->assertRedirect('/home');
    }

    /**
     * Asserts if a user with doctor role trying to access admin dashboard is redirected home
     *
     * @test
     */
    public function user_with_doctor_role_can_access_admin_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'doctor')->first());

        $this->actingAs($user);
        $response = $this->get('/admin/home')->assertRedirect('/home');
    }

    /**
     * Asserts if a user with doctor role trying to access patient dashboard is redirected home
     *
     * @test
     */
    public function user_with_doctor_role_can_access_patient_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'doctor')->first());

        $this->actingAs($user);
        $response = $this->get('/patient/home')->assertRedirect('/home');
    }

    /**
     * Asserts if a user with patient role trying to access admin dashboard is redirected home
     *
     * @test
     */
    public function user_with_patient_role_can_access_admin_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'patient')->first());

        $this->actingAs($user);
        $response = $this->get('/admin/home')->assertRedirect('/home');
    }

    /**
     * Asserts if a user with patient role trying to access doctor dashboard is redirected home
     *
     * @test
     */
    public function user_with_patient_role_can_access_doctor_dashboard()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'patient')->first());

        $this->actingAs($user);
        $response = $this->get('/doctor/home')->assertRedirect('/home');
    }

    /**
     * Asserts if user with admin, doctor and patient roles can access the admin, doctor and patient dashboards
     *
     * @test
     */
    public function user_with_admin_doctor_and_patient_roles_can_access_admin_doctor_and_patient_dashboards()
    {
        $user = factory(User::class)->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());
        $user->roles()->attach(Role::where('name', 'doctor')->first());
        $user->roles()->attach(Role::where('name', 'patient')->first());

        $this->actingAs($user);
        $response = $this->get('/admin/home')->assertOk();
        $response = $this->get('/doctor/home')->assertOk();
        $response = $this->get('/patient/home')->assertOk();
    }
}
