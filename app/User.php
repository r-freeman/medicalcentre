<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_data')
            ->withPivot([
                'insured',
                'policy_no',
                'start_date'
            ]);
    }

    /**
     * @return HasMany
     */
    public function patientVisits()
    {
        return $this->hasMany('App\Visit', 'patient_id');
    }

    /**
     * @return HasMany
     */
    public function doctorVisits()
    {
        return $this->hasMany('App\Visit', 'doctor_id');
    }

    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Add attributes to the User model from role_data pivot table
     *
     * @param $role
     * @param array $pivotCols
     */
    public function addAttributesFromPivot($role, Array $pivotCols)
    {
        foreach ($pivotCols as $col) {
            $this->{$col} = $this->roles->where('name', $role)->first()->pivot->{$col};
        }
    }

    /**
     * Update attributes in the role_data pivot table
     *
     * @param $role
     * @param array $pivotCols
     */
    public function updatePivotAttributes($role, Array $pivotCols)
    {
        $pivot = $this->roles()->where('name', $role)->first()->pivot;
        foreach ($pivotCols as $col => $value) {
            $pivot->{$col} = $value;
            $pivot->save();
        }
    }

    /**
     * Used for detaching roles before a deletion
     *
     * @param array $roles
     */
    public function detachRoles(Array $roles)
    {
        foreach ($roles as $role) {
            $this->roles()->detach($this->roles()->where('name', $role)->first());
        }
    }
}
