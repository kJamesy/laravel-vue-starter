<?php

namespace App;

use App\Notifications\MemberPasswordReset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

class Member extends Authenticatable
{
    use Notifiable;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'active', 'phone'];

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
        'phone' => 'nullable|mobile_phone|numeric|digits_between:10,11|unique:members',
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

    /**
     * Find resource by id
     * @param $id
     * @return mixed
     */
    public static function findResource($id)
    {
        return static::find($id);
    }

    /***
     * Get resources of specified ids
     * @param $ids
     * @param string $orderBy
     * @param string $order
     * @return mixed
     */
    public static function getResourcesByIds($ids, $orderBy = 'first_name', $order = 'asc')
    {
        return static::whereIn('id', (array) $ids)->orderBy($orderBy, $order)->get();
    }

    /**
     * Get all resources with no pagination
     * @param string $orderBy
     * @param string $order
     * @return mixed
     */
    public static function getResourcesNoPagination($orderBy = 'first_name', $order = 'asc')
    {
        return static::orderBy($orderBy, $order)->get();
    }

    /**
     * Get all resources
     * @param string $orderBy
     * @param string $order
     * @param int $paginate
     * @param array $except
     * @return mixed
     */
    public static function getResources($orderBy = 'first_name', $order = 'asc', $paginate = 25, $except = [])
    {
        return static::whereNotIn('id', $except)->orderBy($orderBy, $order)->paginate($paginate);
    }

    /**
     * Get search results
     * @param $search
     * @param int $paginate
     * @param array $except
     * @return mixed
     */
    public static function getSearchResults($search, $paginate = 25, $except = [])
    {
        return static::whereIn('id', static::search($search)->get()->pluck('id'))->whereNotIn('id', $except)->paginate($paginate);
    }
}
