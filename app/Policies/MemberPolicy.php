<?php

namespace App\Policies;

use App\Permissions\UserPermissions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Thing user needs permission for
     * @var array
     */
    protected $policyKey;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $policies = UserPermissions::getPolicies();
        $this->policyKey =  UserPermissions::getModelShortName( (array_search(MemberPolicy::class, $policies)) );
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
        if ( ! $this->policyKey )
            return false;
    }

    /**
     * Determine if the user is allowed to create
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return UserPermissions::hasPermission($user, "create_$this->policyKey");
    }

    /**
     * Determine if the user is allowed to read
     * @param User $user
     * @return bool
     */
    public function read(User $user)
    {
        return UserPermissions::hasPermission($user, "read_$this->policyKey");
    }

    /**
     * Determine if the user is allowed to update
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return UserPermissions::hasPermission($user, "update_$this->policyKey");
    }

    /**
     * Determine if the user is allowed to update
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return UserPermissions::hasPermission($user, "delete_$this->policyKey");
    }

}
