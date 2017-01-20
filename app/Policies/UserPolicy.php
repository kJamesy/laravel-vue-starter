<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Thing user needs permission for
     * @var array
     */
    protected $manage;
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->manage = array_search(self::class, User::$policies);
    }

    /**
     * These guys have a buddy pass
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ( $user->is_super_admin )
            return true;
        elseif ( ! $this->manage )
            return false;
    }

    /**
     * Determine if the user is allowed to create
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return User::hasPermission($user, "create_$this->manage");
    }

    /**
     * Determine if the user is allowed to update
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return User::hasPermission($user, "update_$this->manage");
    }

    /**
     * Determine if the user is allowed to update
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return User::hasPermission($user, "delete_$this->manage");
    }


}
