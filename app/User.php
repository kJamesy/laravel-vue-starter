<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'active', 'meta'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * Custom attributes to append to the User model
     * @var array
     */
    protected $appends = ['name', 'is_super_admin', 'is_user'];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'username' => 'required|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

    /**
     * Get default user role
     * @return string
     */
    public static function getDefaultRole()
    {
        return static::first() ? 'User' : 'Super Administrator';
    }

    /**
     * 'name' accessor
     * @return string
     */
    public function getNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    /**
     * 'is_super_admin' accessor
     * @return bool
     */
    public function getIsSuperAdminAttribute()
    {
        $meta = json_decode($this->meta);

        if ( $meta && property_exists($meta, 'role') )
            return strtolower($meta->role) == 'super administrator';

        return false;
    }

    /**
     * 'is_user' accessor
     * @return bool
     */
    public function getIsUserAttribute()
    {
        $meta = json_decode($this->meta);

        if ( $meta && property_exists($meta, 'role') )
            return strtolower($meta->role) == 'user';

        return false;
    }


}
