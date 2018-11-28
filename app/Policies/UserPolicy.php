<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->can('view all users');
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function view(User $user, User $model)
    {
        return $user->id === $model->id || $user->can('view all users');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('create new users');
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function update(User $user, User $model)
    {
        return $user->can('edit all users');
    }

    /**
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function delete(User $user, User $model)
    {
        return $user->can('destroy all users');
    }
}
