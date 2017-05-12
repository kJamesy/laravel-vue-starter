<?php

namespace App;

use App\Notifications\MemberPasswordReset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'active', 'status', 'phone', 'meta'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Custom attributes
     * @var array
     */
    protected $appends = ['name'];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'username' => 'required|max:255|unique:users|unique:members',
        'email' => 'required|email|max:255|unique:users|unique:members',
        'password' => 'required|min:6|confirmed',
        'phone' => 'sometimes|max:15|unique:members',
    ];

    /**
     * 'name' accessor
     * @return string
     */
    public function getNameAttribute()
    {
        $name = "$this->first_name " . strtoupper($this->last_name);
        return $name;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MemberPasswordReset($token, $this));
    }


}
